<?php
/**
 * Base Model for Lightning 2
 * 
 * All models extend this base class
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace Lightning\Models;

abstract class BaseModel {
    /**
     * Table name for the model
     * @var string
     */
    protected string $table;
    
    /**
     * Fields that can be filled
     * @var array
     */
    protected array $fillable = [];
    
    /**
     * Model data
     * @var array
     */
    protected array $data = [];
    
    /**
     * Get a field value
     * 
     * @param string $key Field name
     * @return mixed Field value
     */
    public function __get(string $key): mixed {
        return $this->data[$key] ?? null;
    }
    
    /**
     * Set a field value
     * 
     * @param string $key Field name
     * @param mixed $value Field value
     */
    public function __set(string $key, mixed $value): void {
        if (in_array($key, $this->fillable)) {
            $this->data[$key] = $value;
        }
    }
    
    /**
     * Fill the model with an array of attributes
     * 
     * @param array $data
     * @return self
     */
    public function fill(array $data): self {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->data[$key] = $value;
            }
        }
        
        return $this;
    }
    
    /**
     * Get all model data as an array
     * 
     * @return array
     */
    public function toArray(): array {
        return $this->data;
    }
}
