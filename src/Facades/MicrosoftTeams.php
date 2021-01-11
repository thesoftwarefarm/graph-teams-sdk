<?php

namespace TheSoftwareFarm\MicrosoftTeams\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TheSoftwareFarm\MicrosoftTeams\MicrosoftTeams
 */
class MicrosoftTeams extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'microsoftteams';
    }
}
