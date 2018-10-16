<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material is strictly forbidden unless prior    |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     16/10/2018
// Project:  AritasIntranet
//
declare(strict_types=1);
namespace CodeInc\DirectoryClassesIterator;


/**
 * Class RecursiveDirectoryClassesIterator
 *
 * @package CodeInc\DirectoryClassesIterator
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RecursiveDirectoryClassesIterator extends AbstractDirectoryClassesIterator
{
    /**
     * @inheritdoc
     * @return \Iterator|\SplFileInfo[]
     */
    protected function getDirectoryIterator():\Iterator
    {
        return new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $this->getPath(),
                \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS
            )
        );
    }
}