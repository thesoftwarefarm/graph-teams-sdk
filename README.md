# Easily connect with Microsoft Graph API
 
Lets you create users and events.

# Installation

Require this package in your `composer.json`. Run the following command:
```bash
composer require thesoftwarefarm/graph-teams-sdk
```

After updating composer, the service provider will automatically be registered and enabled, along with the facade, using Auto-Discovery

Next step is to run the artisan command to bring the config into your project

```bash
php artisan vendor:publish --provider="TheSoftwareFarm\MicrosoftTeams\MicrosoftTeamsServiceProvider" --tag=config
```

Update `config/microsoft_teams.php`
