<?php

abstract class DomainConfig {
    // Path where the Stippers instance runs.
    // If you have Stippers running at yourclub.com/stippers then this is /stippers/.
    const DOMAIN_SUFFIX = '/stippers/';
    
    // Path where the REST API is.
    const API_PATH = "api/";
}
