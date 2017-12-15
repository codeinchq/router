<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     06/12/2017
// Time:     18:54
// Project:  lib-gui
//
namespace CodeInc\GUI\Views;
use CodeInc\ExceptionDisplay\ExceptionRederingEngine;
use CodeInc\ExceptionDisplay\RenderingEngines\BrowserRenderingEngine;
use CodeInc\GUI\Views\Interfaces\ReturnableViewInterface;
use CodeInc\GUI\Views\Interfaces\StringifiableViewInterface;
use CodeInc\GUI\Views\Interfaces\ViewInterface;


/**
 * Class ExceptionDisplay
 *
 * @package CodeInc\GUI\Views
 * @author Joan Fabrégat <joan@codeinc.fr>
 * @deprecated
 * @see BrowserRenderingEngine
 * @see ExceptionRederingEngine
 */
class ExceptionDisplay extends BrowserRenderingEngine implements ViewInterface, StringifiableViewInterface, ReturnableViewInterface {

}