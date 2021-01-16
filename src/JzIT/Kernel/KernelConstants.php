<?php

declare(strict_types=1);

namespace JzIT\Kernel;

interface KernelConstants
{
    public const IS_DEV_MODE = 'IS_DEV_MODE';
    public const ENVIRONMENT = 'ENVIRONMENT';
    public const ENVIRONMENT_DEVELOPMENT = 'development';
    public const ENVIRONMENT_STAGE = 'staging';
    public const ENVIRONMENT_PRODUCTION = 'production';
}
