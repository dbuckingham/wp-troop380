<?php

/**
 * Class cnAddress
 *
 * @since 8.6
 *
 * @property string    $line_1
 * @property string    $line_2
 * @property string    $line_3
 * @property string    $line_4
 * @property string    $district
 * @property string    $county
 * @property string    $locality
 * @property string    $region
 * @property string    $postal_code
 * @property cnCountry $country
 * @property string    $latitude
 * @property string    $longitude
 */
final class cnAddress extends cnEntry_Collection_Item {

	/**
	 * @var string
	 */
	private $line_1 = '';

	/**
	 * @var string
	 */
	private $line_2 = '';

	/**
	 * @var string
	 */
	private $line_3 = '';

	/**
	 * @var string
	 */
	private $line_4 = '';

	/**
	 * @var string
	 */
	private $district = '';

	/**
	 * @var string
	 */
	private $county = '';

	/**
	 * @var string
	 */
	private $locality = '';

	/**
	 * @var string
	 */
	private $region = '';

	/**
	 * @var string
	 */
	private $postal_code = '';

	/**
	 * @var cnCountry
	 */
	private $country = '';

	/**
	 * @var cnCoordinates
	 */
	private $coordinates;

	/**
	 * @var string
	 */
	private $longitude = 0;

	/**
	 * @var string
	 */
	private $latitude = 0;

	/**
	 * @var array
	 */
	private $url;

	/**
	 * Hash map of the old array keys / object properties to cnAddress properties.
	 *
	 * Used in self::__isset()
	 *
	 * @access protected
	 * @since  8.6
	 * @var    array
	 */
	protected $properties = array(
		// 'expected' => 'actual',
		'id'            => 'id',
		'type'          => 'type',
		'name'          => 'name',
		'visibility'    => 'visibility',
		'order'         => 'order',
		'preferred'     => 'preferred',
		'line_1'        => 'line_1',
		'line_2'        => 'line_2',
		'line_3'        => 'line_3',
		'line_4'        => 'line_4',
		'district'      => 'district',
		'county'        => 'county',
		'city'          => 'locality',
		'state'         => 'region',
		'zipcode'       => 'postal_code',
		'latitude'      => 'latitude',
		'longitude'     => 'longitude',
		'country'       => 'country',
		'url'           => 'url',
		// For forward compatibility.
		'locality'      => 'locality',
		'region'        => 'region',
		'postal_code'   => 'postal_code',
		// For back compatibility.
		'address_line1' => 'line_1',
		'address_line2' => 'line_2',
		'line_one'      => 'line_1',
		'line_two'      => 'line_2',
		'line_three'    => 'line_3',
	);

	/**
	 * Hash map of the the old array keys / object properties to cnAddress method callbacks.
	 *
	 * Used in self::__get()
	 *
	 * @access protected
	 * @since  8.6
	 * @var    array
	 */
	protected $methods = array(
		// 'field'   => 'method',
		'id'            => 'getID',
		'type'          => 'getType',
		'name'          => 'getName',
		'visibility'    => 'getVisibility',
		'order'         => 'getOrder',
		'preferred'     => 'getPreferred',
		'line_1'        => 'getLineOne',
		'line_2'        => 'getLineTwo',
		'line_3'        => 'getLineThree',
		'line_4'        => 'getLineFour',
		'district'      => 'getDistrict',
		'county'        => 'getCounty',
		'city'          => 'getLocality',
		'state'         => 'getRegion',
		'zipcode'       => 'getPostalCode',
		'country'       => 'getCountry',
		'latitude'      => 'getLatitude',
		'longitude'     => 'getLongitude',
		'url'           => 'getURLSlugs',
		// For forward compatibility.
		'locality'      => 'getLocality',
		'region'        => 'getRegion',
		'postal_code'   => 'getPostalCode',
		// For back compatibility.
		'address_line1' => 'getLineOne',
		'address_line2' => 'getLineTwo',
		'line_one'      => 'getLineOne',
		'line_two'      => 'getLineTwo',
		'line_three'    => 'getLineThree',
	);

	/**
	 * cnAddress constructor.
	 *
	 * @access public
	 * @since  8.6
	 *
	 * @param array $data
	 */
	public function __construct( $data = array() ) {

		$types   = self::getTypes();
		$default = cnOptions::getDefaultAddressType();

		/** @noinspection DuplicatedCode */
		$this->id = (int) cnArray::get( $data, 'id', 0 );

		$preferred = cnArray::get( $data, 'preferred', false );

		$type = cnSanitize::field( 'attribute', cnArray::get( $data, 'type', key( $default ) ), 'raw' );

		$this->type       = array_key_exists( $type, $types ) ? $type : key( $default );
		$this->visibility = cnSanitize::field( 'attribute', cnArray::get( $data, 'visibility', 'public' ), 'raw' );
		$this->order      = absint( cnArray::get( $data, 'order', 0 ) );
		$this->preferred  = cnFormatting::toBoolean( $preferred );
		$this->line_1     = cnSanitize::field( 'street', cnArray::get( $data, 'line_1', '' ), 'raw' );
		$this->line_2     = cnSanitize::field( 'street', cnArray::get( $data, 'line_2', '' ), 'raw' );
		$this->line_3     = cnSanitize::field( 'street', cnArray::get( $data, 'line_3', '' ), 'raw' );
		$this->line_4     = cnSanitize::field( 'street', cnArray::get( $data, 'line_4', '' ), 'raw' );
		$this->district   = cnSanitize::field( 'district', cnArray::get( $data, 'district', '' ), 'raw' );
		$this->county     = cnSanitize::field( 'county', cnArray::get( $data, 'county', '' ), 'raw' );

		$this->locality = cnSanitize::field(
			'locality',
			cnArray::get( $data, 'locality', cnArray::get( $data, 'city', '' ) ),
			'raw'
		);

		/*
		 * Need to check for `city`, `state` and `zipcode` in the array data for backwards compatibility.
		 */
		$this->region = cnSanitize::field(
			'region',
			cnArray::get( $data, 'region', cnArray::get( $data, 'state', '' ) ),
			'raw'
		);

		$this->postal_code = cnSanitize::field(
			'postal-code',
			cnArray::get( $data, 'postal_code', cnArray::get( $data, 'zipcode', '' ) ),
			'raw'
		);

		$country = array(
			'name'              => cnSanitize::field(
				'country',
				cnArray::get( $data, 'country', '' ),
				'raw'
			),
			'iso_3166_1_alpha2' => cnArray::get( $data, 'country_code', '' ),
		);

		$this->country = new cnCountry( $country );

		$this->coordinates = new cnCoordinates(
			cnArray::get( $data, 'latitude' ),
			cnArray::get( $data, 'longitude' )
		);

		$this->latitude  = $this->coordinates->getLatitude();
		$this->longitude = $this->coordinates->getLongitude();

		// Previous versions set the type to the Select string from the dropdown (bug), so set the name to 'Other'.
		$this->name = ! isset( $types[ $this->type ] ) || 'Select' === $types[ $this->type ] ? 'Other' : $types[ $this->type ];

		// Previous versions saved NULL for visibility under some circumstances (bug), default to public in this case.
		if ( empty( $this->visibility ) ) {

			$this->visibility = 'public';
		}

		$this->url = array(
			'district'    => $this->district,
			'county'      => $this->county,
			'locality'    => $this->locality,
			'region'      => $this->region,
			'postal_code' => $this->postal_code,
			'country'     => $this->country->getName(),
		);
	}

	/**
	 * Make private properties settable for backward compatibility.
	 *
	 * @access public
	 * @since  8.6
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function __set( $key, $value ) {

		if ( isset( $this->properties[ $key ] ) ) {

			$name = $this->properties[ $key ];

			switch ( $name ) {

				case 'latitude':
					$this->coordinates->setLatitude( $value );
					break;

				case 'longitude':
					$this->coordinates->setLongitude( $value );
					break;

				default:
					$this->$name = $value;
			}

			// $this->$name = $value;
		}
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param  string $key
	 *
	 * @return void
	 */
	public function __unset( $key ) {

		if ( isset( $this->properties[ $key ] ) ) {

			$name = $this->properties[ $key ];

			switch ( $name ) {

				case 'latitude':
					$this->coordinates->setLatitude( null );
					break;

				case 'longitude':
					$this->coordinates->setLongitude( null );
					break;

				default:
					unset( $this->$name );
			}

			// unset( $this->$name );
		}
	}

	/**
	 * Return an array of registered address types.
	 *
	 * @access private
	 * @since  8.6
	 *
	 * @return array
	 */
	private static function getTypes() {

		return cnOptions::getAddressTypeOptions();
	}

	/**
	 * Escaped or sanitize cnAddress based on context.
	 *
	 * @access public
	 * @since  8.6
	 *
	 * @param cnAddress $self
	 * @param string    $context
	 *
	 * @return cnAddress
	 */
	protected function prepareContext( $self, $context ) {

		$self->id          = absint( $self->id );
		$self->type        = cnSanitize::field( 'attribute', $self->type, $context );
		$self->visibility  = cnSanitize::field( 'attribute', $self->visibility, $context );
		$self->order       = absint( $self->order );
		$self->preferred   = cnFormatting::toBoolean( $self->preferred );
		$self->line_1      = cnSanitize::field( 'street', $self->line_1, $context );
		$self->line_2      = cnSanitize::field( 'street', $self->line_2, $context );
		$self->line_3      = cnSanitize::field( 'street', $self->line_3, $context );
		$self->line_4      = cnSanitize::field( 'street', $self->line_4, $context );
		$self->district    = cnSanitize::field( 'district', $self->district, $context );
		$self->county      = cnSanitize::field( 'county', $self->county, $context );
		$self->locality    = cnSanitize::field( 'locality', $self->locality, $context );
		$self->region      = cnSanitize::field( 'region', $self->region, $context );
		$self->postal_code = cnSanitize::field( 'postal-code', $self->postal_code, $context );
		$self->country     = new cnCountry(
			array(
				'name'              => cnSanitize::field( 'country', $self->country->getName(), $context ),
				'iso_3166_1_alpha2' => cnSanitize::field( 'attribute', $self->country->getIsoAlpha2(), $context ),
			)
		);

		return $self;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string
	 */
	public function getLineOne() {

		return $this->line_1;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param string $street
	 *
	 * @return cnAddress
	 */
	public function setLineOne( $street ) {

		$this->line_1 = cnSanitize::field( 'street', $street, 'raw' );

		return $this;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string
	 */
	public function getLineTwo() {

		return $this->line_2;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param string $street
	 *
	 * @return cnAddress
	 */
	public function setLineTwo( $street ) {

		$this->line_2 = cnSanitize::field( 'street', $street, 'raw' );

		return $this;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string
	 */
	public function getLineThree() {

		return $this->line_3;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param string $street
	 *
	 * @return cnAddress
	 */
	public function setLineThree( $street ) {

		$this->line_3 = cnSanitize::field( 'street', $street, 'raw' );

		return $this;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string
	 */
	public function getLineFour() {

		return $this->line_4;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param string $street
	 *
	 * @return cnAddress
	 */
	public function setLineFour( $street ) {

		$this->line_4 = cnSanitize::field( 'street', $street, 'raw' );

		return $this;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string
	 */
	public function getDistrict() {

		return $this->district;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param string $district
	 *
	 * @return cnAddress
	 */
	public function setDistrict( $district ) {

		$this->district = cnSanitize::field( 'district', $district, 'raw' );

		return $this;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string
	 */
	public function getCounty() {

		return $this->county;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param string $county
	 *
	 * @return cnAddress
	 */
	public function setCounty( $county ) {

		$this->county = cnSanitize::field( 'county', $county, 'raw' );

		return $this;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string
	 */
	public function getLocality() {

		return $this->locality;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param string $locality
	 *
	 * @return cnAddress
	 */
	public function setLocality( $locality ) {

		$this->locality = cnSanitize::field( 'locality', $locality, 'raw' );

		return $this;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string
	 */
	public function getRegion() {

		return $this->region;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param string $region
	 *
	 * @return cnAddress
	 */
	public function setRegion( $region ) {

		$this->region = cnSanitize::field( 'region', $region, 'raw' );

		return $this;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string
	 */
	public function getPostalCode() {

		return $this->postal_code;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param string $postal_code
	 *
	 * @return cnAddress
	 */
	public function setPostalCode( $postal_code ) {

		$this->postal_code = cnSanitize::field( 'postal-code', $postal_code, 'raw' );

		return $this;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string
	 */
	public function getCountry() {

		return $this->country->getName();
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param string $country
	 * @param string $code
	 *
	 * @return cnAddress
	 */
	public function setCountry( $country, $code = '' ) {

		$this->country = new cnCountry(
			array(
				'name'              => cnSanitize::field( 'country', $country, 'raw' ),
				'iso_3166_1_alpha2' => $code,
			)
		);

		return $this;
	}

	/**
	 * @since 10.3
	 *
	 * @return array
	 */
	public function getURLSlugs() {

		return $this->url;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @param float $latitude
	 * @param float $longitude
	 *
	 * @return cnAddress
	 */
	public function setCoordinates( $latitude, $longitude ) {

		if ( null === $latitude || null === $longitude ) {

			$this->coordinates = null;
		}

		$this->coordinates = new cnCoordinates( $latitude, $longitude );

		$this->latitude  = $this->coordinates->getLatitude();
		$this->longitude = $this->coordinates->getLongitude();

		return $this;
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string|null
	 */
	public function getLatitude() {

		if ( null === $this->coordinates ) {
			return null;
		}

		return $this->coordinates->getLatitude();
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return string|null
	 */
	public function getLongitude() {

		if ( null === $this->coordinates ) {
			return null;
		}

		return $this->coordinates->getLongitude();
	}

	/**
	 * Utilize the Google Maps TimeZone API to get the time zone info of the address.
	 *
	 * @access public
	 * @since  8.6.10
	 *
	 * @return cnTimezone|WP_Error An instance of cnTimezone on success and WP_Error instance on failure.
	 */
	public function getTimezone() {

		// Create GoogleMapsTimeZone object with default properties.
		$gmtAPI = new cnGoogleMapsTimeZone( $this->getLatitude(), $this->getLongitude() );

		// Perform query
		return $gmtAPI->queryTimeZone();
	}

	/**
	 * @access public
	 * @since  8.6
	 *
	 * @return array
	 */
	public function toArray() {

		$address = array(
			'id'          => $this->id,
			'type'        => $this->type,
			'visibility'  => $this->visibility,
			'order'       => $this->order,
			'preferred'   => $this->preferred,
			'line_1'      => $this->line_1,
			'line_2'      => $this->line_2,
			'line_3'      => $this->line_3,
			'line_4'      => $this->line_4,
			'district'    => $this->district,
			'county'      => $this->county,
			'locality'    => $this->locality,
			'region'      => $this->region,
			'postal_code' => $this->postal_code,
			'country'     => $this->country->getName(),
			'latitude'    => $this->coordinates->getLatitude(),
			'longitude'   => $this->coordinates->getLongitude(),
			'url'         => $this->url,
		);

		$address['name'] = $this->getName();

		// For backward compatibility.
		$address['address_line1'] =& $this->line_1;
		$address['address_line2'] =& $this->line_2;

		$address['line_one']   =& $this->line_1;
		$address['line_two']   =& $this->line_2;
		$address['line_three'] =& $this->line_3;

		$address['city']    =& $this->locality;
		$address['state']   =& $this->region;
		$address['zipcode'] =& $this->postal_code;

		return $address;
	}
}
