<?php


namespace Models {


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
             * @var string
             * @file
             * @SQLAttributes not null
             */
            $image;
    }
}