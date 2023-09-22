<?php namespace Waka\Cloudis\Models;

use Model;

/**
 * biblio Model
 */

class Biblio extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    use \Waka\Cloudis\Classes\Traits\CloudiTrait;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'waka_cloudis_biblios';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:waka_cloudis_biblios',
    ];

    public $customMessages = [
        'name.required' => 'waka.cloudis::lang.models.biblio.e.name',
        'slug.required' => 'waka.cloudis::lang.models.biblio.e.slug',
    ];

    /**
     * @var array attributes send to datasource for creating document
     */
    public $attributesToDs = [
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [
        'options',
    ];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [
    ];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
    ];
    public $hasOneThrough = [];
    public $hasManyThrough = [
    ];
    public $belongsTo = [
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [
    ];
    public $morphMany = [
    ];
    public $attachOne = [
        'src' => 'Waka\Cloudis\Models\CloudiFile',
        'srcv' => 'Waka\Cloudis\Models\CloudiFile',
    ];
    public $attachMany = [
        'srcs' => 'Waka\Cloudis\Models\CloudiFile',
    ];

    /**
     *EVENTS
     **/


    /**
     * LISTS
     **/
    public function listTypeImage()
    {
        return ['image' => 'Image', 'images' => 'Images', 'video' => 'Vidéo', 'raw' => 'raw'];
    }

    /**
     * GETTERS
     **/
    public function getCloudiLinkAttribute() {
        
        if($this->type == 'image' && $this->src) {
            return "<a href='".$this->src->getCloudiUrl('150','150',null,'pad')."' target='_blank'><img src='" . $this->src->getColumnThumb() . "'></a>";
        } elseif($this->srcv) {
            return "<a href='".$this->getVideoUrl()."' target='_blank'>lien</a>";
        } else {
            return $this->type;
        }
    }

    public function getVideoUrl($options = [])
    {
        $biblioOption = $this->getOptions();
        $formatOption = array_merge($biblioOption, $options);
        //trace_log($formatOption);
        return $this->srcv->getVideoUrl($formatOption);
    }

    public function getOptions() {
        if(!$this->options) {
            return [];
        } else {
            return \Yaml::parse($this->options);
        }
        
    }
    /**
     * SCOPES
     */

    /**
     * SETTERS
     */

    /**
     * FILTER FIELDS
     */
    public function filterFields($fields, $context = null)
    {
        
        if (isset($fields->src) || isset($fields->srcv)) {
            if ($this->type == 'image') {
                $fields->srcs->hidden = true;
                $fields->srcv->hidden = true;
                $fields->src->hidden = false;
            } elseif ($this->type == 'images') {
                $fields->srcs->hidden = false;
                $fields->srcv->hidden = true;
                $fields->src->hidden = true;
            } else {
                $fields->srcs->hidden = true;
                $fields->src->hidden = true;
                $fields->srcv->hidden = false;
            }
        }
    }

    /**
     * OTHERS
     */
}
