<?php


namespace Models {


    use DateTime;
    use Exception;

    class ProjectImage
    {
        public
            /**
             * @var int
             * @SQLType int
             * @SQLAttributes auto_increment not null primary key
             */
            $id,
            /**
             * @var int
             * @SQLType int
             * @SQLAttributes references projects(id)
             */
            $projectId,
            /**
             * @var string
             * @SQLType varchar(200)
             */
            $title,
            /**
             * @var float
             * @SQLType float
             */
            $price,
            /**
             * @var string
             * @SQLType varchar(100)
             */
            $size,
            /**
             * @var DateTime
             * @SQLType datetime
             */
            $datePainted,
            /**
             * @var string
             * @SQLType varchar(100)
             */
            $state,
            /**
             * @var string
             * @file
             * @SQLAttributes not null
             */
            $image;

        public function __set($name, $value)
        {
            try {
                $this->$name = $name == 'datePainted' ? new DateTime($value) : $value;
            } catch (Exception $e) {
                $this->$name = $value;
            }
        }
    }
}