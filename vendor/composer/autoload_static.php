<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit97c71020a7cafd01839f91f069a1d8c2
{
    public static $files = array (
        '130721a315b6cbfa4b8e7bb569d9f42d' => __DIR__ . '/../..' . '/app/config/config.php',
        'bfe4a8e97427872757c16be70c96f2a2' => __DIR__ . '/../..' . '/app/helpers/url_helper.php',
        'ec87a2bc832d1091cef7e14364cc3428' => __DIR__ . '/../..' . '/app/helpers/session_helper.php',
        '218d3231369082426166ddbfdfa63656' => __DIR__ . '/../..' . '/app/helpers/log_helper.php',
    );

    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'App\\Controllers\\Assignments' => __DIR__ . '/../..' . '/app/Controllers/Assignments.php',
        'App\\Controllers\\CertificateTypes' => __DIR__ . '/../..' . '/app/Controllers/CertificateTypes.php',
        'App\\Controllers\\Certificates' => __DIR__ . '/../..' . '/app/Controllers/Certificates.php',
        'App\\Controllers\\Companies' => __DIR__ . '/../..' . '/app/Controllers/Companies.php',
        'App\\Controllers\\Dashboard' => __DIR__ . '/../..' . '/app/Controllers/Dashboard.php',
        'App\\Controllers\\Drivers' => __DIR__ . '/../..' . '/app/Controllers/Drivers.php',
        'App\\Controllers\\Entries' => __DIR__ . '/../..' . '/app/Controllers/Entries.php',
        'App\\Controllers\\Fuel' => __DIR__ . '/../..' . '/app/Controllers/Fuel.php',
        'App\\Controllers\\Insurance' => __DIR__ . '/../..' . '/app/Controllers/Insurance.php',
        'App\\Controllers\\LicenseTypes' => __DIR__ . '/../..' . '/app/Controllers/LicenseTypes.php',
        'App\\Controllers\\Licenses' => __DIR__ . '/../..' . '/app/Controllers/Licenses.php',
        'App\\Controllers\\Logs' => __DIR__ . '/../..' . '/app/Controllers/Logs.php',
        'App\\Controllers\\Maintenance' => __DIR__ . '/../..' . '/app/Controllers/Maintenance.php',
        'App\\Controllers\\Pages' => __DIR__ . '/../..' . '/app/Controllers/Pages.php',
        'App\\Controllers\\Purchases' => __DIR__ . '/../..' . '/app/Controllers/Purchases.php',
        'App\\Controllers\\Reports' => __DIR__ . '/../..' . '/app/Controllers/Reports.php',
        'App\\Controllers\\Settings' => __DIR__ . '/../..' . '/app/Controllers/Settings.php',
        'App\\Controllers\\Tanks' => __DIR__ . '/../..' . '/app/Controllers/Tanks.php',
        'App\\Controllers\\Transfers' => __DIR__ . '/../..' . '/app/Controllers/Transfers.php',
        'App\\Controllers\\Users' => __DIR__ . '/../..' . '/app/Controllers/Users.php',
        'App\\Controllers\\Vehicles' => __DIR__ . '/../..' . '/app/Controllers/Vehicles.php',
        'App\\Core\\App' => __DIR__ . '/../..' . '/app/Core/App.php',
        'App\\Core\\Controller' => __DIR__ . '/../..' . '/app/Core/Controller.php',
        'App\\Core\\Database' => __DIR__ . '/../..' . '/app/Core/Database.php',
        'App\\Models\\Assignment' => __DIR__ . '/../..' . '/app/Models/Assignment.php',
        'App\\Models\\CertificateType' => __DIR__ . '/../..' . '/app/Models/CertificateType.php',
        'App\\Models\\Company' => __DIR__ . '/../..' . '/app/Models/Company.php',
        'App\\Models\\Driver' => __DIR__ . '/../..' . '/app/Models/Driver.php',
        'App\\Models\\DriverCertificate' => __DIR__ . '/../..' . '/app/Models/DriverCertificate.php',
        'App\\Models\\FuelModel' => __DIR__ . '/../..' . '/app/Models/FuelModel.php',
        'App\\Models\\FuelPurchase' => __DIR__ . '/../..' . '/app/Models/FuelPurchase.php',
        'App\\Models\\FuelTank' => __DIR__ . '/../..' . '/app/Models/FuelTank.php',
        'App\\Models\\FuelTransfer' => __DIR__ . '/../..' . '/app/Models/FuelTransfer.php',
        'App\\Models\\License' => __DIR__ . '/../..' . '/app/Models/License.php',
        'App\\Models\\LicenseType' => __DIR__ . '/../..' . '/app/Models/LicenseType.php',
        'App\\Models\\Log' => __DIR__ . '/../..' . '/app/Models/Log.php',
        'App\\Models\\MaintenanceModel' => __DIR__ . '/../..' . '/app/Models/MaintenanceModel.php',
        'App\\Models\\User' => __DIR__ . '/../..' . '/app/Models/User.php',
        'App\\Models\\Vehicle' => __DIR__ . '/../..' . '/app/Models/Vehicle.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit97c71020a7cafd01839f91f069a1d8c2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit97c71020a7cafd01839f91f069a1d8c2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit97c71020a7cafd01839f91f069a1d8c2::$classMap;

        }, null, ClassLoader::class);
    }
}
