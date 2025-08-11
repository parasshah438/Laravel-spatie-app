<?php

if (!function_exists('activity')) {
    /**
     * Create a new activity log entry using our custom Activity model.
     *
     * @param string $description
     * @return \App\Models\Activity
     */
    function activity(string $description = null)
    {
        if ($description) {
            // Direct logging
            return \App\Models\Activity::log($description);
        }
        
        // Return a fluent activity builder
        return new class {
            private $causer = null;
            private $subject = null;
            private $properties = [];
            
            public function causedBy($causer)
            {
                $this->causer = $causer;
                return $this;
            }
            
            public function performedOn($subject)
            {
                $this->subject = $subject;
                return $this;
            }
            
            public function withProperties(array $properties)
            {
                $this->properties = $properties;
                return $this;
            }
            
            public function log(string $description)
            {
                return \App\Models\Activity::log(
                    $description,
                    $this->subject,
                    $this->causer,
                    $this->properties
                );
            }
        };
    }
}
