<?php

abstract class SecurityConfig {
    /*
    Be aware when changing the NPASSWORDHASHITERATIONS value. When it is changed nobody, including the admin, will be able to login. Only users created after the value     changed will be able to login. Old users can only login again when the value is restored, but this will lock out users created with the new value.
    For this reason it's reccommended to set this value to your likings once during database setup and not touch it ever again.
    */
    const NPASSWORDHASHITERATIONS = 50000;
}