<?php


namespace Models {


    class Project
    {
        public
            /**
             * @var int
             * @SQLType int
             * @SQLAttributes auto_increment not null primary key
             */
            $id,
            /**
             * @var string
             * @SQLType varchar(200)
             */
            $title,
            $subtitle,
            $description;
    }
}