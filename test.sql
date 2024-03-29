EXPLAIN SELECT
          `Project`.`ID`,
          `Project`.`Code`,
          `Project`.`Name`,
          `Project`.`Require`,
          `Project`.`RequireDevide`,
          `Project`.`InputDate`,
          `Project`.`CompTime`,
          `Project`.`Quantity`,
          `Project`.`InitSize`,
          `Project`.`CompSize`,
          `Project`.`ExamTime`,
          `Project`.`CheckExamTime`,
          `Project`.`UpTime`,
          `Project`.`Status_id`,
          `Project`.`Customer_id`,
          `Project`.`UrlFolder`,
          `Project`.`File`,
          `Project`.`IsActivated`,
          `Project`.`IsSpecial`,
          `Project`.`User_id`,
          `Project`.`UserReview`,
          `Project`.`HasCheck`,
          `Project`.`SpentTime`,
          `Project`.`ProductType_id`,
          `Project`.`ProcessType_id`,
          `Project`.`ProjectType_id`,
          `Project`.`CustomerGroup_id`,
          `Project`.`Note`,
          `Project`.`returnTime`,
          `Project`.`Parent_id`,
          `Project`.`auto`,
          `Project`.`RealQuantity`,
          `Project`.`product_extension_id`,
          `Project`.`process_type_id`,
          `Project`.`user_download`,
          `Project`.`user_khac`,
          `Project`.`project_weight`,
          `Project`.`complete_link`,
          `Project`.`email_1`,
          `Project`.`Email`,
          `Project`.`EmailCc`,
          `Project`.`activemail`,
          `Project`.`ActiveTime`,
          `User`.`id`,
          `User`.`group_id`,
          `User`.`username`,
          `User`.`password`,
          `User`.`avatar`,
          `User`.`language`,
          `User`.`timezone`,
          `User`.`key`,
          `User`.`status`,
          `User`.`created`,
          `User`.`modified`,
          `User`.`last_login`,
          `User`.`date_of_birth`,
          `User`.`start_work_day`,
          `User`.`day_work_official`,
          `User`.`id_number`,
          `User`.`phone`,
          `User`.`address`,
          `User`.`skype`,
          `User`.`name_bank`,
          `User`.`username_bank`,
          `User`.`department_id`,
          `User`.`number_bank`,
          `User`.`code_staff`,
          `User`.`gender`,
          `User`.`email`,
          `User`.`name`,
          `User`.`ftp`,
          `User`.`ftp_username`,
          `User`.`ftp_password`,
          `User`.`basic_salary`,
          `User`.`daily_wage`,
          `User`.`lunch_allowance`,
          `User`.`parking_allowance`,
          `User`.`night_shift_allowance`,
          `User`.`BHXH_voluntary`,
          `User`.`BHXH_staff`,
          `User`.`BHXH_company`,
          `User`.`Note`,
          `User`.`overtime_allowance`,
          `User`.`customer_group_id`,
          `Status`.`ID`,
          `Status`.`Name`,
          `Status`.`Desc`,
          `Status`.`oder`,
          `Customer`.`id`,
          `Customer`.`country_id`,
          `Customer`.`address`,
          `Customer`.`email`,
          `Customer`.`phone`,
          `Customer`.`website`,
          `Customer`.`connector`,
          `Customer`.`skype`,
          `Customer`.`ftp`,
          `Customer`.`order`,
          `Customer`.`status`,
          `Customer`.`name`,
          `Customer`.`ftp_username`,
          `Customer`.`ftp_password`,
          `Customer`.`customer_code`,
          `CustomerGroup`.`id`,
          `CustomerGroup`.`name`,
          `CustomerGroup`.`country_id`,
          `CustomerGroup`.`customer_id`,
          `CustomerGroup`.`deliver_time`,
          `CustomerGroup`.`sharing_note`,
          `CustomerGroup`.`doing_note`,
          `CustomerGroup`.`init_note`,
          `CustomerGroup`.`help_file`,
          `CustomerGroup`.`upload_file`,
          `CustomerGroup`.`product_extension_id`,
          `CustomerGroup`.`process_type_id`,
          `CustomerGroup`.`com_ids`,
          `CustomerGroup`.`order`,
          `CustomerGroup`.`status`,
          `CustomerGroup`.`done_test`,
          `ProjectType`.`ID`,
          `ProjectType`.`Name`,
          `ProjectType`.`Desc`,
          `ProcessType`.`id`,
          `ProcessType`.`point`,
          `ProcessType`.`time_counting`,
          `ProcessType`.`note`,
          `ProcessType`.`order`,
          `ProcessType`.`status`,
          `ProcessType`.`name`,
          `ProcessType`.`process_type_group_id`,
          `ProcessType`.`number`,
          `ProcessType`.`time_checkbox`,
          `ProductType`.`id`,
          `ProductType`.`name`,
          `ProductType`.`product_category_id`,
          `ProductType`.`description`,
          `ProductType`.`order`,
          `ProductType`.`status`,
          `Productextension`.`id`,
          `Productextension`.`name`,
          `Productextension`.`view_type`,
          `Productextension`.`description`,
          `Productextension`.`order`,
          `Productextension`.`status`
        FROM `workmanlocal`.`projects` AS `Project` LEFT JOIN `workmanlocal`.`users` AS `User`
            ON (`Project`.`User_id` = `User`.`id`)
          LEFT JOIN `workmanlocal`.`statuses` AS `Status` ON (`Project`.`Status_id` = `Status`.`id`)
          LEFT JOIN `workmanlocal`.`customers` AS `Customer` ON (`Project`.`Customer_id` = `Customer`.`id`)
          LEFT JOIN `workmanlocal`.`customer_groups` AS `CustomerGroup`
            ON (`Project`.`CustomerGroup_id` = `CustomerGroup`.`id`)
          LEFT JOIN `workmanlocal`.`project_types` AS `ProjectType` ON (`Project`.`ProjectType_id` = `ProjectType`.`id`)
          LEFT JOIN `workmanlocal`.`process_types` AS `ProcessType` ON (`Project`.`ProcessType_id` = `ProcessType`.`id`)
          LEFT JOIN `workmanlocal`.`product_types` AS `ProductType` ON (`Project`.`ProductType_id` = `ProductType`.`id`)
          LEFT JOIN `workmanlocal`.`product_extensions` AS `Productextension`
            ON (`Project`.`product_extension_id` = `Productextension`.`id`)
        WHERE 1 = 1
        ORDER BY `Project`.`id`
          DESC
        LIMIT 5