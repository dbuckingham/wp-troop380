<?php

class Merit_Badge {

    public static $all;

    public static function __constructStatic() {
        
        static::$all = array(
            'american-business'   	        => new Merit_Badge( 'American Business' ),
            'american-cultures'   	        => new Merit_Badge( 'American Cultures' ),
            'american-heritage' 	        => new Merit_Badge( 'American Heritage' ),
            'american-labor'  		        => new Merit_Badge( 'American Labor' ),
            'animal-science'  		        => new Merit_Badge( 'Animal Science' ),
            'animation'   			        => new Merit_Badge( 'Animation' ),
            'archaeology'			        => new Merit_Badge( 'Archaeology' ),
            'archery'				        => new Merit_Badge( 'Archery' ),
            'architecture'			        => new Merit_Badge( 'Architecture' ),
            'art'					        => new Merit_Badge( 'Art' ),
            'astronomy'				        => new Merit_Badge( 'Astronomy' ),
            'athletics'				        => new Merit_Badge( 'Athletics' ),
            'automotive-maintenance'        => new Merit_Badge( 'Automotive Maintenance' ),
            'aviation'				        => new Merit_Badge( 'Aviation' ),
            'backpacking'			        => new Merit_Badge( 'Backpacking' ),
            'basketry'				        => new Merit_Badge( 'Basketry' ),
            'bird-study'			        => new Merit_Badge( 'Bird Study' ),
            'bugling'				        => new Merit_Badge( 'Bugling' ),
            'camping'				        => new Merit_Badge( 'Camping', true ),
            'canoeing'				        => new Merit_Badge( 'Canoeing' ),
            'chemistry'                     => new Merit_Badge( 'Chemistry' ),
            'chess'                         => new Merit_Badge( 'Chess' ),
            'citizenship-in-the-community'  => new Merit_Badge( 'Citizenship in the Community', true ),
            'citizenship-in-the-nation'     => new Merit_Badge( 'Citizenship in the Nation', true ),
            'citizenship-in-society'        => new Merit_Badge( 'Citizenship in Society', true ),
            'citizenship-in-the-world'      => new Merit_Badge( 'Citizenship in the World', true ),
            'climbing'                      => new Merit_Badge( 'Climbing' ),
            'coin-collecting'               => new Merit_Badge( 'Coin Collecting' ),
            'collections'                   => new Merit_Badge( 'Collections' ),
            'communication'                 => new Merit_Badge( 'Communication', true ),
            'composite-materials'           => new Merit_Badge( 'Composite Materials' ),
            'cooking'                       => new Merit_Badge( 'Cooking', true ),
            'crime-prevention'              => new Merit_Badge( 'Crime Prevention' ),
            'cycling'                       => new Merit_Badge( 'Cycling', true ),
            'dentistry'                     => new Merit_Badge( 'Dentistry' ),
            'digital-technology'            => new Merit_Badge( 'Digital Technology' ),
            'disabilities-awareness'        => new Merit_Badge( 'Disabilities Awareness' ),
            'dog-care'                      => new Merit_Badge( 'Dog Care' ),
            'drafting'                      => new Merit_Badge( 'Drafting' ),
            'electricity'                   => new Merit_Badge( 'Electricity' ),
            'electronics'                   => new Merit_Badge( 'Electronics' ),
            'emergency-preparedness'        => new Merit_Badge( 'Emergency Preparedness', true ),
            'energy'                        => new Merit_Badge( 'Energy' ),
            'engineering'                   => new Merit_Badge( 'Engineering' ),
            'entrepreneurship'              => new Merit_Badge( 'Entrepreneurship' ),
            'environmental-science'         => new Merit_Badge( 'Environmental Science', true ),
            'exploration'                   => new Merit_Badge( 'Exploration' ),
            'family-life'                   => new Merit_Badge( 'Family Life', true ),
            'farm-mechanics'                => new Merit_Badge( 'Farm Mechanics' ),
            'fingerprinting'                => new Merit_Badge( 'Fingerprinting' ),
            'fire-safety'                   => new Merit_Badge( 'Fire Safety' ),
            'first-aid'                     => new Merit_Badge( 'First Aid', true ),
            'fish-wildlife-management'      => new Merit_Badge( 'Fish & Wildlife Management' ),
            'fishing'                       => new Merit_Badge( 'Fishing' ),
            'fly=fishing'                   => new Merit_Badge( 'Fly Fishing' ),
            'forestry'                      => new Merit_Badge( 'Forestry' ),
            'game-design'                   => new Merit_Badge( 'Game Design' ),
            'gardening'                     => new Merit_Badge( 'Gardening' ),
            'genealogy'                     => new Merit_Badge( 'Genealogy' ),
            'geocaching'                    => new Merit_Badge( 'Geocaching' ),
            'geology'                       => new Merit_Badge( 'Geology' ),
            'golf'                          => new Merit_Badge( 'Golf' ),
            'graphic-arts'                  => new Merit_Badge( 'Graphic Arts' ),
            'health-care-professions'       => new Merit_Badge( 'Health Care Professions' ),
            'hiking'                        => new Merit_Badge( 'Hiking', true ),
            'home-repairs'                  => new Merit_Badge( 'Home Repairs' ),
            'horsemanship'                  => new Merit_Badge( 'Horsemanship' ),
            'indian-lore'                   => new Merit_Badge( 'Indian Lore' ),
            'insect-study'                  => new Merit_Badge( 'Insect Study' ),
            'inventing'                     => new Merit_Badge( 'Inventing' ),
            'journalism'                    => new Merit_Badge( 'Journalism' ),
            'kayaking'                      => new Merit_Badge( 'Kayaking' ),
            'landscape-architecture'        => new Merit_Badge( 'Landscape Architecture' ),
            'law'                           => new Merit_Badge( 'Law' ),
            'leatherwork'                   => new Merit_Badge( 'Leatherwork' ),
            'lifesaving'                    => new Merit_Badge( 'Lifesaving', true ),
            'mammal-study'                  => new Merit_Badge( 'Mammal Study' ),
            'metalwork'                     => new Merit_Badge( 'Metalwork' ),
            'mining-in-society'             => new Merit_Badge( 'Mining in Society' ),
            'model-design-and-building'     => new Merit_Badge( 'Model Design and Building' ),
            'motorboating'                  => new Merit_Badge( 'Motorboating' ),
            'moviemaking'                   => new Merit_Badge( 'Moviemaking' ),
            'music'                         => new Merit_Badge( 'Music' ),
            'nature'                        => new Merit_Badge( 'Nature' ),
            'nuclear-science'               => new Merit_Badge( 'Nuclear Science' ),
            'oceanography'                  => new Merit_Badge( 'Oceanography' ),
            'orienteering'                  => new Merit_Badge( 'Orienteering' ),
            'painting'                      => new Merit_Badge( 'Painting' ),
            'personal-fitness'              => new Merit_Badge( 'Personal Fitness', true ),
            'personal-management'           => new Merit_Badge( 'Personal Management', true ),
            'pets'                          => new Merit_Badge( 'Pets' ),
            'photography'                   => new Merit_Badge( 'Photography' ),
            'pioneering'                    => new Merit_Badge( 'Pioneering' ),
            'plant-science'                 => new Merit_Badge( 'Plant Science' ),
            'plumbing'                      => new Merit_Badge( 'Plumbing' ),
            'pottery'                       => new Merit_Badge( 'Pottery' ),
            'programming'                   => new Merit_Badge( 'Programming' ),
            'public-health'                 => new Merit_Badge( 'Public Health' ),
            'public-speaking'               => new Merit_Badge( 'Public Speaking' ),
            'pulp-and-paper'                => new Merit_Badge( 'Pulp and Paper' ),
            'radio'                         => new Merit_Badge( 'Radio' ),
            'railroading'                   => new Merit_Badge( 'Railroading' ),
            'reading'                       => new Merit_Badge( 'Reading' ),
            'reptile-amphibian-study'       => new Merit_Badge( 'Reptile and Amphibian Study' ),
            'rifle-shooting'                => new Merit_Badge( 'Rifle Shooting' ),
            'robotics'                      => new Merit_Badge( 'Robotics' ),
            'rowing'                        => new Merit_Badge( 'Rowing' ),
            'safety'                        => new Merit_Badge( 'Safety', true ),
            'salesmanship'                  => new Merit_Badge( 'Salesmanship' ),
            'scholarship'                   => new Merit_Badge( 'Scholarship' ),
            'scouting-heritage'             => new Merit_Badge( 'Scouting Heritage' ),
            'scuba-diving'                  => new Merit_Badge( 'Scuba Diving' ),
            'sculpture'                     => new Merit_Badge( 'Sculpture' ),
            'search-and-rescue'             => new Merit_Badge( 'Search and Rescue' ),
            'shotgun-shooting'              => new Merit_Badge( 'Shotgun Shooting' ),
            'signs-signals-and-codes'       => new Merit_Badge( 'Signs, Signals, and Codes' ),
            'skating'                       => new Merit_Badge( 'Skating' ),
            'small-boat sailing'            => new Merit_Badge( 'Small-boat salinng' ),
            'snow-sports'                   => new Merit_Badge( 'Snow Sports' ),
            'soil-water-conservation'       => new Merit_Badge( 'Soil and Water Conservation' ),
            'space-exploration'             => new Merit_Badge( 'Space Exploration' ),
            'sports'                        => new Merit_Badge( 'Sports' ),
            'stamp-collecting'              => new Merit_Badge( 'Stamp Collecting' ),
            'surveying'                     => new Merit_Badge( 'Surveying' ),
            'sustainability'                => new Merit_Badge( 'Sustainability', true ),
            'swimming'                      => new Merit_Badge( 'Swimming', true ),
            'textile'                       => new Merit_Badge( 'Textile' ),
            'theater'                       => new Merit_Badge( 'Theater' ),
            'traffic-safety'                => new Merit_Badge( 'Traffic Safety' ),
            'truck-transportation'          => new Merit_Badge( 'Truck Transportation' ),
            'veterinary-medicine'           => new Merit_Badge( 'Veterinary Medicine' ),
            'water-sports'                  => new Merit_Badge( 'Water Sports' ),
            'weather'                       => new Merit_Badge( 'Weather' ),
            'welding'                       => new Merit_Badge( 'Welding' ),
            'whitewater'                    => new Merit_Badge( 'Whitewater' ),
            'wilderness-survival'           => new Merit_Badge( 'Wilderness Survival' ),
            'wood-carving'                  => new Merit_Badge( 'Wood Carving' ),
            'woodwork'                      => new Merit_Badge( 'Woodwork' )
        );
    }

    static function merit_badge_names() {

        return array_map( function($mb) { return $mb->name; }, static::$all );
    } 

    public $name;
    public $is_eagle_required = false;

    function __construct( $name, $is_eagle_required = false ) {
        $this->name = $name;
        $this->is_eagle_required = $is_eagle_required;
    }
}

?>