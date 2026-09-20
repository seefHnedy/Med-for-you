DELIMITER //

CREATE OR REPLACE PROCEDURE process_medicine_donation(
    IN p_medicine_id INT
)
BEGIN
    DECLARE v_current_inprocess_recipe_medicine_id INT;
    DECLARE v_current_inprocess_qty INT;
    DECLARE v_available_qty INT;
    DECLARE v_recipe_id INT;
    
    DECLARE v_new_recipe_medicine_id INT;
    DECLARE v_new_recipe_id INT;
    DECLARE v_new_qty INT;
    DECLARE v_new_priority INT;
    DECLARE v_new_days INT;
    DECLARE v_score DECIMAL(10,2);
    
    DECLARE v_done INT DEFAULT 0;
    DECLARE v_continue_loop INT DEFAULT 1;
    
    DECLARE cur_inprocess CURSOR FOR
        SELECT 
            rm.id,
            rm.qty,
            rm.recipe_id,
            m.available_qty
        FROM recipe_medicines rm
        INNER JOIN medicines m ON rm.medicine_id = m.id
        WHERE rm.medicine_id = p_medicine_id
        AND rm.status = 'in_process'
        LIMIT 1;
    
    DECLARE cur_initial CURSOR FOR
        SELECT 
            rm.id,
            rm.recipe_id,
            rm.qty,
            CASE 
                WHEN r.priority = 'emergency' THEN 2
                WHEN r.priority = 'urgent' THEN 1
                WHEN r.priority = 'normal' THEN 0
                ELSE 0
            END AS priority_value,
            DATEDIFF(NOW(), r.created_at) AS days_waiting,
            (rm.qty + DATEDIFF(NOW(), r.created_at) + 
             CASE 
                WHEN r.priority = 'emergency' THEN 2
                WHEN r.priority = 'urgent' THEN 1
                WHEN r.priority = 'normal' THEN 0
                ELSE 0
             END) / rm.qty AS score
        FROM recipe_medicines rm
        INNER JOIN recipes r ON rm.recipe_id = r.id
        WHERE rm.medicine_id = p_medicine_id
        AND rm.status = 'initial'
        ORDER BY score DESC
        LIMIT 1;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

CREATE TABLE IF NOT EXISTS temp_recipe_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
    
   DELETE FROM temp_recipe_results;


CREATE TEMPORARY TABLE IF NOT EXISTS temp_finished_recipes (
        recipe_id INT PRIMARY KEY,
        finished_at DATETIME DEFAULT NOW()
    );
    
    DELETE FROM temp_finished_recipes;
    
    START TRANSACTION;
    
    SELECT available_qty INTO v_available_qty
    FROM medicines
    WHERE id = p_medicine_id
    FOR UPDATE;
    
    main_loop: LOOP
        SET v_done = 0;
        
       OPEN cur_inprocess;
        FETCH cur_inprocess INTO 
            v_current_inprocess_recipe_medicine_id,
            v_current_inprocess_qty,
            v_recipe_id,
            v_available_qty;
        
        IF NOT v_done THEN
            
            IF v_available_qty >= v_current_inprocess_qty THEN
                
                UPDATE medicines 
                SET available_qty = available_qty - v_current_inprocess_qty
                WHERE id = p_medicine_id;
                
                UPDATE recipe_medicines 
                SET status = 'finished',
                    updated_at = NOW()
                WHERE id = v_current_inprocess_recipe_medicine_id;
                
               IF NOT EXISTS (
                    SELECT 1 
                    FROM recipe_medicines 
                    WHERE recipe_id = v_recipe_id 
                    AND status != 'finished'
                ) THEN
                    UPDATE recipes 
                    SET status = 'finished',
                        updated_at = NOW()
                    WHERE id = v_recipe_id;

		   INSERT IGNORE INTO temp_finished_recipes (recipe_id, finished_at) 
                    VALUES (v_recipe_id, NOW());

                END IF;
                
               SELECT available_qty INTO v_available_qty
                FROM medicines
                WHERE id = p_medicine_id;
                
                CLOSE cur_inprocess;
                
               SET v_done = 0;
                OPEN cur_initial;
                FETCH cur_initial INTO 
                    v_new_recipe_medicine_id,
                    v_new_recipe_id,
                    v_new_qty,
                    v_new_priority,
                    v_new_days,
                    v_score;
                
                IF NOT v_done THEN
                   UPDATE recipe_medicines 
                    SET status = 'in_process',
                        updated_at = NOW()
                    WHERE id = v_new_recipe_medicine_id;
                    
                    CLOSE cur_initial;
                    ITERATE main_loop;
                ELSE
                    CLOSE cur_initial;
                    LEAVE main_loop;
                END IF;
                
            ELSE
                CLOSE cur_inprocess;
                LEAVE main_loop;
            END IF;
            
        ELSE
            CLOSE cur_inprocess;
            SET v_done = 0;
            
           OPEN cur_initial;
            FETCH cur_initial INTO 
                v_new_recipe_medicine_id,
                v_new_recipe_id,
                v_new_qty,
                v_new_priority,
                v_new_days,
                v_score;
            
            IF NOT v_done THEN
               UPDATE recipe_medicines 
                SET status = 'in_process',
                    updated_at = NOW()
                WHERE id = v_new_recipe_medicine_id;
                
                CLOSE cur_initial;
               ITERATE main_loop;
            ELSE
                CLOSE cur_initial;
                LEAVE main_loop;
            END IF;
        END IF;
        
    END LOOP main_loop;
    
    COMMIT;

	INSERT INTO temp_recipe_results (recipe_id)
    SELECT recipe_id 
    FROM temp_finished_recipes;

END //

DELIMITER ;