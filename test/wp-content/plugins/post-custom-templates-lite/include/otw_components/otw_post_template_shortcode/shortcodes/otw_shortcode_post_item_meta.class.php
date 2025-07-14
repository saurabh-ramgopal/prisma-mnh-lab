<?php
class OTW_Post_Template_Shortcode_Post_Item_Meta extends OTW_Post_Template_Shortcodes{
	
	public function __construct(){
		
		$this->has_options = true;
		
		$this->has_custom_options = true;
		
		$this->has_preview = false;
		
		$this->custom_styles = array();
		
		$this->shortcode_id = '';
		
		$this->google_fonts = array();
		
		$this->fonts_to_include = array();
		
		parent::__construct();
		
		$this->shortcode_name = 'otw_shortcode_post_item_meta';
	}
	/**
	 * register external libs
	 */
	public function register_external_libs(){
	
		$this->add_external_lib( 'css', 'otw-shortcode-font-awesome', $this->component_url.'css/font-awesome.min.css', 'all', 10 );
	}
	
	/**
	 * apply settings
	 */
	public function apply_settings(){
		
		$this->settings = array(
			'items' => array(
				'author'  => $this->get_label( 'author' ),
				'date'  => $this->get_label( 'date' ),
				'category'  => $this->get_label( 'category' ),
				'tags'  => $this->get_label( 'tags' ),
				'comments'  => $this->get_label( 'comments' ),
				'views'  => $this->get_label( 'Post Visits' )
			),
			'default_items' => 'date,category',
			'labels' => array(
				'labels' => $this->get_label( 'Labels(default)' ),
				'icons'  => $this->get_label( 'Icons' ),
				'none'   => $this->get_label( 'No Label' )
			),
			'default_labels' => 'yes',
			'types' => array(
				'horizontal' => $this->get_label( 'Horizontal (default)' ),
				'vertical'  => $this->get_label( 'Vertical' )
			),
			'default_types' => 'horizontal',
			'separators' => array(
				'yes' => $this->get_label( 'Yes (default)' ),
				'no'  => $this->get_label( 'No' )
			),
			'default_separators' => 'horizontal',
			'sizes' => array(
				''   => $this->get_label( 'None (default)' ),
				'8'  => '8px',
				'10' => '10px',
				'12' => '12px',
				'14' => '14px',
				'16' => '16px',
				'18' => '18px',
				'20' => '20px',
				'22' => '22px',
				'24' => '24px',
				'26' => '26px',
				'28' => '28px',
				'30' => '30px',
				'32' => '32px',
				'34' => '34px',
				'36' => '36px',
				'38' => '38px',
				'40' => '40px',
				'42' => '42px',
				'44' => '44px',
				'46' => '46px',
				'48' => '48px',
				'50' => '50px',
				'52' => '52px',
				'54' => '54px',
				'56' => '56px',
				'58' => '58px',
				'60' => '60px'
			),
			'default_size' => '',
			'styles' => array(
				''            => $this->get_label( 'None (default)' ),
				'regular'     => $this->get_label( 'Regular' ),
				'bold'        => $this->get_label( 'Bold' ),
				'italic'      => $this->get_label( 'Italic' ),
				'bold_italic' => $this->get_label( 'Bold and Italic' )
			),
			'default_style' => '',
			'fonts' => array(
				''            => $this->get_label( 'None (default)' ),
			),
			'default_font' => '',
			'alignments' => array(
				''            => $this->get_label( 'None (default)' ),
				'left'        => $this->get_label( 'Left' ),
				'right'       => $this->get_label( 'Right' ),
				'center'      => $this->get_label( 'Center' )
			),
			'default_alignment' => ''
		);
		
		$fonts = json_decode( '[{"id":0,"text":"Arial"},{"id":1,"text":"Arial Black"},{"id":2,"text":"Comic Sans"},{"id":3,"text":"Georgia"},{"id":4,"text":"Impact"},{"id":5,"text":"Lucida Console"},{"id":6,"text":"Lucida Sans Unicode"},{"id":7,"text":"Tahoma"},{"id":8,"text":"Times New Roman"},{"id":9,"text":"Verdana"},{"id":10,"text":"ABeeZee"},{"id":11,"text":"Abel"},{"id":12,"text":"Abril Fatface"},{"id":13,"text":"Aclonica"},{"id":14,"text":"Acme"},{"id":15,"text":"Actor"},{"id":16,"text":"Adamina"},{"id":17,"text":"Advent Pro"},{"id":18,"text":"Aguafina Script"},{"id":19,"text":"Akronim"},{"id":20,"text":"Aladin"},{"id":21,"text":"Aldrich"},{"id":22,"text":"Alef"},{"id":23,"text":"Alegreya"},{"id":24,"text":"Alegreya SC"},{"id":25,"text":"Alegreya Sans"},{"id":26,"text":"Alegreya Sans SC"},{"id":27,"text":"Alex Brush"},{"id":28,"text":"Alfa Slab One"},{"id":29,"text":"Alice"},{"id":30,"text":"Alike"},{"id":31,"text":"Alike Angular"},{"id":32,"text":"Allan"},{"id":33,"text":"Allerta"},{"id":34,"text":"Allerta Stencil"},{"id":35,"text":"Allura"},{"id":36,"text":"Almendra"},{"id":37,"text":"Almendra Display"},{"id":38,"text":"Almendra SC"},{"id":39,"text":"Amarante"},{"id":40,"text":"Amaranth"},{"id":41,"text":"Amatic SC"},{"id":42,"text":"Amethysta"},{"id":43,"text":"Anaheim"},{"id":44,"text":"Andada"},{"id":45,"text":"Andika"},{"id":46,"text":"Angkor"},{"id":47,"text":"Annie Use Your Telescope"},{"id":48,"text":"Anonymous Pro"},{"id":49,"text":"Antic"},{"id":50,"text":"Antic Didone"},{"id":51,"text":"Antic Slab"},{"id":52,"text":"Anton"},{"id":53,"text":"Arapey"},{"id":54,"text":"Arbutus"},{"id":55,"text":"Arbutus Slab"},{"id":56,"text":"Architects Daughter"},{"id":57,"text":"Archivo Black"},{"id":58,"text":"Archivo Narrow"},{"id":59,"text":"Arimo"},{"id":60,"text":"Arizonia"},{"id":61,"text":"Armata"},{"id":62,"text":"Artifika"},{"id":63,"text":"Arvo"},{"id":64,"text":"Asap"},{"id":65,"text":"Asset"},{"id":66,"text":"Astloch"},{"id":67,"text":"Asul"},{"id":68,"text":"Atomic Age"},{"id":69,"text":"Aubrey"},{"id":70,"text":"Audiowide"},{"id":71,"text":"Autour One"},{"id":72,"text":"Average"},{"id":73,"text":"Average Sans"},{"id":74,"text":"Averia Gruesa Libre"},{"id":75,"text":"Averia Libre"},{"id":76,"text":"Averia Sans Libre"},{"id":77,"text":"Averia Serif Libre"},{"id":78,"text":"Bad Script"},{"id":79,"text":"Balthazar"},{"id":80,"text":"Bangers"},{"id":81,"text":"Basic"},{"id":82,"text":"Battambang"},{"id":83,"text":"Baumans"},{"id":84,"text":"Bayon"},{"id":85,"text":"Belgrano"},{"id":86,"text":"Belleza"},{"id":87,"text":"BenchNine"},{"id":88,"text":"Bentham"},{"id":89,"text":"Berkshire Swash"},{"id":90,"text":"Bevan"},{"id":91,"text":"Bigelow Rules"},{"id":92,"text":"Bigshot One"},{"id":93,"text":"Bilbo"},{"id":94,"text":"Bilbo Swash Caps"},{"id":95,"text":"Bitter"},{"id":96,"text":"Black Ops One"},{"id":97,"text":"Bokor"},{"id":98,"text":"Bonbon"},{"id":99,"text":"Boogaloo"},{"id":100,"text":"Bowlby One"},{"id":101,"text":"Bowlby One SC"},{"id":102,"text":"Brawler"},{"id":103,"text":"Bree Serif"},{"id":104,"text":"Bubblegum Sans"},{"id":105,"text":"Bubbler One"},{"id":106,"text":"Buda"},{"id":107,"text":"Buenard"},{"id":108,"text":"Butcherman"},{"id":109,"text":"Butterfly Kids"},{"id":110,"text":"Cabin"},{"id":111,"text":"Cabin Condensed"},{"id":112,"text":"Cabin Sketch"},{"id":113,"text":"Caesar Dressing"},{"id":114,"text":"Cagliostro"},{"id":115,"text":"Calligraffitti"},{"id":116,"text":"Cambo"},{"id":117,"text":"Candal"},{"id":118,"text":"Cantarell"},{"id":119,"text":"Cantata One"},{"id":120,"text":"Cantora One"},{"id":121,"text":"Capriola"},{"id":122,"text":"Cardo"},{"id":123,"text":"Carme"},{"id":124,"text":"Carrois Gothic"},{"id":125,"text":"Carrois Gothic SC"},{"id":126,"text":"Carter One"},{"id":127,"text":"Caudex"},{"id":128,"text":"Cedarville Cursive"},{"id":129,"text":"Ceviche One"},{"id":130,"text":"Changa One"},{"id":131,"text":"Chango"},{"id":132,"text":"Chau Philomene One"},{"id":133,"text":"Chela One"},{"id":134,"text":"Chelsea Market"},{"id":135,"text":"Chenla"},{"id":136,"text":"Cherry Cream Soda"},{"id":137,"text":"Cherry Swash"},{"id":138,"text":"Chewy"},{"id":139,"text":"Chicle"},{"id":140,"text":"Chivo"},{"id":141,"text":"Cinzel"},{"id":142,"text":"Cinzel Decorative"},{"id":143,"text":"Clicker Script"},{"id":144,"text":"Coda"},{"id":145,"text":"Coda Caption"},{"id":146,"text":"Codystar"},{"id":147,"text":"Combo"},{"id":148,"text":"Comfortaa"},{"id":149,"text":"Coming Soon"},{"id":150,"text":"Concert One"},{"id":151,"text":"Condiment"},{"id":152,"text":"Content"},{"id":153,"text":"Contrail One"},{"id":154,"text":"Convergence"},{"id":155,"text":"Cookie"},{"id":156,"text":"Copse"},{"id":157,"text":"Corben"},{"id":158,"text":"Courgette"},{"id":159,"text":"Cousine"},{"id":160,"text":"Coustard"},{"id":161,"text":"Covered By Your Grace"},{"id":162,"text":"Crafty Girls"},{"id":163,"text":"Creepster"},{"id":164,"text":"Crete Round"},{"id":165,"text":"Crimson Text"},{"id":166,"text":"Croissant One"},{"id":167,"text":"Crushed"},{"id":168,"text":"Cuprum"},{"id":169,"text":"Cutive"},{"id":170,"text":"Cutive Mono"},{"id":171,"text":"Damion"},{"id":172,"text":"Dancing Script"},{"id":173,"text":"Dangrek"},{"id":174,"text":"Dawning of a New Day"},{"id":175,"text":"Days One"},{"id":176,"text":"Delius"},{"id":177,"text":"Delius Swash Caps"},{"id":178,"text":"Delius Unicase"},{"id":179,"text":"Della Respira"},{"id":180,"text":"Denk One"},{"id":181,"text":"Devonshire"},{"id":182,"text":"Didact Gothic"},{"id":183,"text":"Diplomata"},{"id":184,"text":"Diplomata SC"},{"id":185,"text":"Domine"},{"id":186,"text":"Donegal One"},{"id":187,"text":"Doppio One"},{"id":188,"text":"Dorsa"},{"id":189,"text":"Dosis"},{"id":190,"text":"Dr Sugiyama"},{"id":191,"text":"Droid Sans"},{"id":192,"text":"Droid Sans Mono"},{"id":193,"text":"Droid Serif"},{"id":194,"text":"Duru Sans"},{"id":195,"text":"Dynalight"},{"id":196,"text":"EB Garamond"},{"id":197,"text":"Eagle Lake"},{"id":198,"text":"Eater"},{"id":199,"text":"Economica"},{"id":200,"text":"Electrolize"},{"id":201,"text":"Elsie"},{"id":202,"text":"Elsie Swash Caps"},{"id":203,"text":"Emblema One"},{"id":204,"text":"Emilys Candy"},{"id":205,"text":"Engagement"},{"id":206,"text":"Englebert"},{"id":207,"text":"Enriqueta"},{"id":208,"text":"Erica One"},{"id":209,"text":"Esteban"},{"id":210,"text":"Euphoria Script"},{"id":211,"text":"Ewert"},{"id":212,"text":"Exo"},{"id":213,"text":"Exo 2"},{"id":214,"text":"Expletus Sans"},{"id":215,"text":"Fanwood Text"},{"id":216,"text":"Fascinate"},{"id":217,"text":"Fascinate Inline"},{"id":218,"text":"Faster One"},{"id":219,"text":"Fasthand"},{"id":220,"text":"Fauna One"},{"id":221,"text":"Federant"},{"id":222,"text":"Federo"},{"id":223,"text":"Felipa"},{"id":224,"text":"Fenix"},{"id":225,"text":"Finger Paint"},{"id":226,"text":"Fjalla One"},{"id":227,"text":"Fjord One"},{"id":228,"text":"Flamenco"},{"id":229,"text":"Flavors"},{"id":230,"text":"Fondamento"},{"id":231,"text":"Fontdiner Swanky"},{"id":232,"text":"Forum"},{"id":233,"text":"Francois One"},{"id":234,"text":"Freckle Face"},{"id":235,"text":"Fredericka the Great"},{"id":236,"text":"Fredoka One"},{"id":237,"text":"Freehand"},{"id":238,"text":"Fresca"},{"id":239,"text":"Frijole"},{"id":240,"text":"Fruktur"},{"id":241,"text":"Fugaz One"},{"id":242,"text":"GFS Didot"},{"id":243,"text":"GFS Neohellenic"},{"id":244,"text":"Gabriela"},{"id":245,"text":"Gafata"},{"id":246,"text":"Galdeano"},{"id":247,"text":"Galindo"},{"id":248,"text":"Gentium Basic"},{"id":249,"text":"Gentium Book Basic"},{"id":250,"text":"Geo"},{"id":251,"text":"Geostar"},{"id":252,"text":"Geostar Fill"},{"id":253,"text":"Germania One"},{"id":254,"text":"Gilda Display"},{"id":255,"text":"Give You Glory"},{"id":256,"text":"Glass Antiqua"},{"id":257,"text":"Glegoo"},{"id":258,"text":"Gloria Hallelujah"},{"id":259,"text":"Goblin One"},{"id":260,"text":"Gochi Hand"},{"id":261,"text":"Gorditas"},{"id":262,"text":"Goudy Bookletter 1911"},{"id":263,"text":"Graduate"},{"id":264,"text":"Grand Hotel"},{"id":265,"text":"Gravitas One"},{"id":266,"text":"Great Vibes"},{"id":267,"text":"Griffy"},{"id":268,"text":"Gruppo"},{"id":269,"text":"Gudea"},{"id":270,"text":"Habibi"},{"id":271,"text":"Hammersmith One"},{"id":272,"text":"Hanalei"},{"id":273,"text":"Hanalei Fill"},{"id":274,"text":"Handlee"},{"id":275,"text":"Hanuman"},{"id":276,"text":"Happy Monkey"},{"id":277,"text":"Headland One"},{"id":278,"text":"Henny Penny"},{"id":279,"text":"Herr Von Muellerhoff"},{"id":280,"text":"Holtwood One SC"},{"id":281,"text":"Homemade Apple"},{"id":282,"text":"Homenaje"},{"id":283,"text":"IM Fell DW Pica"},{"id":284,"text":"IM Fell DW Pica SC"},{"id":285,"text":"IM Fell Double Pica"},{"id":286,"text":"IM Fell Double Pica SC"},{"id":287,"text":"IM Fell English"},{"id":288,"text":"IM Fell English SC"},{"id":289,"text":"IM Fell French Canon"},{"id":290,"text":"IM Fell French Canon SC"},{"id":291,"text":"IM Fell Great Primer"},{"id":292,"text":"IM Fell Great Primer SC"},{"id":293,"text":"Iceberg"},{"id":294,"text":"Iceland"},{"id":295,"text":"Imprima"},{"id":296,"text":"Inconsolata"},{"id":297,"text":"Inder"},{"id":298,"text":"Indie Flower"},{"id":299,"text":"Inika"},{"id":300,"text":"Irish Grover"},{"id":301,"text":"Istok Web"},{"id":302,"text":"Italiana"},{"id":303,"text":"Italianno"},{"id":304,"text":"Jacques Francois"},{"id":305,"text":"Jacques Francois Shadow"},{"id":306,"text":"Jim Nightshade"},{"id":307,"text":"Jockey One"},{"id":308,"text":"Jolly Lodger"},{"id":309,"text":"Josefin Sans"},{"id":310,"text":"Josefin Slab"},{"id":311,"text":"Joti One"},{"id":312,"text":"Judson"},{"id":313,"text":"Julee"},{"id":314,"text":"Julius Sans One"},{"id":315,"text":"Junge"},{"id":316,"text":"Jura"},{"id":317,"text":"Just Another Hand"},{"id":318,"text":"Just Me Again Down Here"},{"id":319,"text":"Kameron"},{"id":320,"text":"Kantumruy"},{"id":321,"text":"Karla"},{"id":322,"text":"Kaushan Script"},{"id":323,"text":"Kavoon"},{"id":324,"text":"Kdam Thmor"},{"id":325,"text":"Keania One"},{"id":326,"text":"Kelly Slab"},{"id":327,"text":"Kenia"},{"id":328,"text":"Khmer"},{"id":329,"text":"Kite One"},{"id":330,"text":"Knewave"},{"id":331,"text":"Kotta One"},{"id":332,"text":"Koulen"},{"id":333,"text":"Kranky"},{"id":334,"text":"Kreon"},{"id":335,"text":"Kristi"},{"id":336,"text":"Krona One"},{"id":337,"text":"La Belle Aurore"},{"id":338,"text":"Lancelot"},{"id":339,"text":"Lato"},{"id":340,"text":"League Script"},{"id":341,"text":"Leckerli One"},{"id":342,"text":"Ledger"},{"id":343,"text":"Lekton"},{"id":344,"text":"Lemon"},{"id":345,"text":"Libre Baskerville"},{"id":346,"text":"Life Savers"},{"id":347,"text":"Lilita One"},{"id":348,"text":"Lily Script One"},{"id":349,"text":"Limelight"},{"id":350,"text":"Linden Hill"},{"id":351,"text":"Lobster"},{"id":352,"text":"Lobster Two"},{"id":353,"text":"Londrina Outline"},{"id":354,"text":"Londrina Shadow"},{"id":355,"text":"Londrina Sketch"},{"id":356,"text":"Londrina Solid"},{"id":357,"text":"Lora"},{"id":358,"text":"Love Ya Like A Sister"},{"id":359,"text":"Loved by the King"},{"id":360,"text":"Lovers Quarrel"},{"id":361,"text":"Luckiest Guy"},{"id":362,"text":"Lusitana"},{"id":363,"text":"Lustria"},{"id":364,"text":"Macondo"},{"id":365,"text":"Macondo Swash Caps"},{"id":366,"text":"Magra"},{"id":367,"text":"Maiden Orange"},{"id":368,"text":"Mako"},{"id":369,"text":"Marcellus"},{"id":370,"text":"Marcellus SC"},{"id":371,"text":"Marck Script"},{"id":372,"text":"Margarine"},{"id":373,"text":"Marko One"},{"id":374,"text":"Marmelad"},{"id":375,"text":"Marvel"},{"id":376,"text":"Mate"},{"id":377,"text":"Mate SC"},{"id":378,"text":"Maven Pro"},{"id":379,"text":"McLaren"},{"id":380,"text":"Meddon"},{"id":381,"text":"MedievalSharp"},{"id":382,"text":"Medula One"},{"id":383,"text":"Megrim"},{"id":384,"text":"Meie Script"},{"id":385,"text":"Merienda"},{"id":386,"text":"Merienda One"},{"id":387,"text":"Merriweather"},{"id":388,"text":"Merriweather Sans"},{"id":389,"text":"Metal"},{"id":390,"text":"Metal Mania"},{"id":391,"text":"Metamorphous"},{"id":392,"text":"Metrophobic"},{"id":393,"text":"Michroma"},{"id":394,"text":"Milonga"},{"id":395,"text":"Miltonian"},{"id":396,"text":"Miltonian Tattoo"},{"id":397,"text":"Miniver"},{"id":398,"text":"Miss Fajardose"},{"id":399,"text":"Modern Antiqua"},{"id":400,"text":"Molengo"},{"id":401,"text":"Molle"},{"id":402,"text":"Monda"},{"id":403,"text":"Monofett"},{"id":404,"text":"Monoton"},{"id":405,"text":"Monsieur La Doulaise"},{"id":406,"text":"Montaga"},{"id":407,"text":"Montez"},{"id":408,"text":"Montserrat"},{"id":409,"text":"Montserrat Alternates"},{"id":410,"text":"Montserrat Subrayada"},{"id":411,"text":"Moul"},{"id":412,"text":"Moulpali"},{"id":413,"text":"Mountains of Christmas"},{"id":414,"text":"Mouse Memoirs"},{"id":415,"text":"Mr Bedfort"},{"id":416,"text":"Mr Dafoe"},{"id":417,"text":"Mr De Haviland"},{"id":418,"text":"Mrs Saint Delafield"},{"id":419,"text":"Mrs Sheppards"},{"id":420,"text":"Muli"},{"id":421,"text":"Mystery Quest"},{"id":422,"text":"Neucha"},{"id":423,"text":"Neuton"},{"id":424,"text":"New Rocker"},{"id":425,"text":"News Cycle"},{"id":426,"text":"Niconne"},{"id":427,"text":"Nixie One"},{"id":428,"text":"Nobile"},{"id":429,"text":"Nokora"},{"id":430,"text":"Norican"},{"id":431,"text":"Nosifer"},{"id":432,"text":"Nothing You Could Do"},{"id":433,"text":"Noticia Text"},{"id":434,"text":"Noto Sans"},{"id":435,"text":"Noto Serif"},{"id":436,"text":"Nova Cut"},{"id":437,"text":"Nova Flat"},{"id":438,"text":"Nova Mono"},{"id":439,"text":"Nova Oval"},{"id":440,"text":"Nova Round"},{"id":441,"text":"Nova Script"},{"id":442,"text":"Nova Slim"},{"id":443,"text":"Nova Square"},{"id":444,"text":"Numans"},{"id":445,"text":"Nunito"},{"id":446,"text":"Odor Mean Chey"},{"id":447,"text":"Offside"},{"id":448,"text":"Old Standard TT"},{"id":449,"text":"Oldenburg"},{"id":450,"text":"Oleo Script"},{"id":451,"text":"Oleo Script Swash Caps"},{"id":452,"text":"Open Sans"},{"id":453,"text":"Open Sans Condensed"},{"id":454,"text":"Oranienbaum"},{"id":455,"text":"Orbitron"},{"id":456,"text":"Oregano"},{"id":457,"text":"Orienta"},{"id":458,"text":"Original Surfer"},{"id":459,"text":"Oswald"},{"id":460,"text":"Over the Rainbow"},{"id":461,"text":"Overlock"},{"id":462,"text":"Overlock SC"},{"id":463,"text":"Ovo"},{"id":464,"text":"Oxygen"},{"id":465,"text":"Oxygen Mono"},{"id":466,"text":"PT Mono"},{"id":467,"text":"PT Sans"},{"id":468,"text":"PT Sans Caption"},{"id":469,"text":"PT Sans Narrow"},{"id":470,"text":"PT Serif"},{"id":471,"text":"PT Serif Caption"},{"id":472,"text":"Pacifico"},{"id":473,"text":"Paprika"},{"id":474,"text":"Parisienne"},{"id":475,"text":"Passero One"},{"id":476,"text":"Passion One"},{"id":477,"text":"Pathway Gothic One"},{"id":478,"text":"Patrick Hand"},{"id":479,"text":"Patrick Hand SC"},{"id":480,"text":"Patua One"},{"id":481,"text":"Paytone One"},{"id":482,"text":"Peralta"},{"id":483,"text":"Permanent Marker"},{"id":484,"text":"Petit Formal Script"},{"id":485,"text":"Petrona"},{"id":486,"text":"Philosopher"},{"id":487,"text":"Piedra"},{"id":488,"text":"Pinyon Script"},{"id":489,"text":"Pirata One"},{"id":490,"text":"Plaster"},{"id":491,"text":"Play"},{"id":492,"text":"Playball"},{"id":493,"text":"Playfair Display"},{"id":494,"text":"Playfair Display SC"},{"id":495,"text":"Podkova"},{"id":496,"text":"Poiret One"},{"id":497,"text":"Poller One"},{"id":498,"text":"Poly"},{"id":499,"text":"Pompiere"},{"id":500,"text":"Pontano Sans"},{"id":501,"text":"Port Lligat Sans"},{"id":502,"text":"Port Lligat Slab"},{"id":503,"text":"Prata"},{"id":504,"text":"Preahvihear"},{"id":505,"text":"Press Start 2P"},{"id":506,"text":"Princess Sofia"},{"id":507,"text":"Prociono"},{"id":508,"text":"Prosto One"},{"id":509,"text":"Puritan"},{"id":510,"text":"Purple Purse"},{"id":511,"text":"Quando"},{"id":512,"text":"Quantico"},{"id":513,"text":"Quattrocento"},{"id":514,"text":"Quattrocento Sans"},{"id":515,"text":"Questrial"},{"id":516,"text":"Quicksand"},{"id":517,"text":"Quintessential"},{"id":518,"text":"Qwigley"},{"id":519,"text":"Racing Sans One"},{"id":520,"text":"Radley"},{"id":521,"text":"Raleway"},{"id":522,"text":"Raleway Dots"},{"id":523,"text":"Rambla"},{"id":524,"text":"Rammetto One"},{"id":525,"text":"Ranchers"},{"id":526,"text":"Rancho"},{"id":527,"text":"Rationale"},{"id":528,"text":"Redressed"},{"id":529,"text":"Reenie Beanie"},{"id":530,"text":"Revalia"},{"id":531,"text":"Ribeye"},{"id":532,"text":"Ribeye Marrow"},{"id":533,"text":"Righteous"},{"id":534,"text":"Risque"},{"id":535,"text":"Roboto"},{"id":536,"text":"Roboto Condensed"},{"id":537,"text":"Roboto Slab"},{"id":538,"text":"Rochester"},{"id":539,"text":"Rock Salt"},{"id":540,"text":"Rokkitt"},{"id":541,"text":"Romanesco"},{"id":542,"text":"Ropa Sans"},{"id":543,"text":"Rosario"},{"id":544,"text":"Rosarivo"},{"id":545,"text":"Rouge Script"},{"id":546,"text":"Ruda"},{"id":547,"text":"Rufina"},{"id":548,"text":"Ruge Boogie"},{"id":549,"text":"Ruluko"},{"id":550,"text":"Rum Raisin"},{"id":551,"text":"Ruslan Display"},{"id":552,"text":"Russo One"},{"id":553,"text":"Ruthie"},{"id":554,"text":"Rye"},{"id":555,"text":"Sacramento"},{"id":556,"text":"Sail"},{"id":557,"text":"Salsa"},{"id":558,"text":"Sanchez"},{"id":559,"text":"Sancreek"},{"id":560,"text":"Sansita One"},{"id":561,"text":"Sarina"},{"id":562,"text":"Satisfy"},{"id":563,"text":"Scada"},{"id":564,"text":"Schoolbell"},{"id":565,"text":"Seaweed Script"},{"id":566,"text":"Sevillana"},{"id":567,"text":"Seymour One"},{"id":568,"text":"Shadows Into Light"},{"id":569,"text":"Shadows Into Light Two"},{"id":570,"text":"Shanti"},{"id":571,"text":"Share"},{"id":572,"text":"Share Tech"},{"id":573,"text":"Share Tech Mono"},{"id":574,"text":"Shojumaru"},{"id":575,"text":"Short Stack"},{"id":576,"text":"Siemreap"},{"id":577,"text":"Sigmar One"},{"id":578,"text":"Signika"},{"id":579,"text":"Signika Negative"},{"id":580,"text":"Simonetta"},{"id":581,"text":"Sintony"},{"id":582,"text":"Sirin Stencil"},{"id":583,"text":"Six Caps"},{"id":584,"text":"Skranji"},{"id":585,"text":"Slackey"},{"id":586,"text":"Smokum"},{"id":587,"text":"Smythe"},{"id":588,"text":"Sniglet"},{"id":589,"text":"Snippet"},{"id":590,"text":"Snowburst One"},{"id":591,"text":"Sofadi One"},{"id":592,"text":"Sofia"},{"id":593,"text":"Sonsie One"},{"id":594,"text":"Sorts Mill Goudy"},{"id":595,"text":"Source Code Pro"},{"id":596,"text":"Source Sans Pro"},{"id":597,"text":"Special Elite"},{"id":598,"text":"Spicy Rice"},{"id":599,"text":"Spinnaker"},{"id":600,"text":"Spirax"},{"id":601,"text":"Squada One"},{"id":602,"text":"Stalemate"},{"id":603,"text":"Stalinist One"},{"id":604,"text":"Stardos Stencil"},{"id":605,"text":"Stint Ultra Condensed"},{"id":606,"text":"Stint Ultra Expanded"},{"id":607,"text":"Stoke"},{"id":608,"text":"Strait"},{"id":609,"text":"Sue Ellen Francisco"},{"id":610,"text":"Sunshiney"},{"id":611,"text":"Supermercado One"},{"id":612,"text":"Suwannaphum"},{"id":613,"text":"Swanky and Moo Moo"},{"id":614,"text":"Syncopate"},{"id":615,"text":"Tangerine"},{"id":616,"text":"Taprom"},{"id":617,"text":"Tauri"},{"id":618,"text":"Telex"},{"id":619,"text":"Tenor Sans"},{"id":620,"text":"Text Me One"},{"id":621,"text":"The Girl Next Door"},{"id":622,"text":"Tienne"},{"id":623,"text":"Tinos"},{"id":624,"text":"Titan One"},{"id":625,"text":"Titillium Web"},{"id":626,"text":"Trade Winds"},{"id":627,"text":"Trocchi"},{"id":628,"text":"Trochut"},{"id":629,"text":"Trykker"},{"id":630,"text":"Tulpen One"},{"id":631,"text":"Ubuntu"},{"id":632,"text":"Ubuntu Condensed"},{"id":633,"text":"Ubuntu Mono"},{"id":634,"text":"Ultra"},{"id":635,"text":"Uncial Antiqua"},{"id":636,"text":"Underdog"},{"id":637,"text":"Unica One"},{"id":638,"text":"UnifrakturCook"},{"id":639,"text":"UnifrakturMaguntia"},{"id":640,"text":"Unkempt"},{"id":641,"text":"Unlock"},{"id":642,"text":"Unna"},{"id":643,"text":"VT323"},{"id":644,"text":"Vampiro One"},{"id":645,"text":"Varela"},{"id":646,"text":"Varela Round"},{"id":647,"text":"Vast Shadow"},{"id":648,"text":"Vibur"},{"id":649,"text":"Vidaloka"},{"id":650,"text":"Viga"},{"id":651,"text":"Voces"},{"id":652,"text":"Volkhov"},{"id":653,"text":"Vollkorn"},{"id":654,"text":"Voltaire"},{"id":655,"text":"Waiting for the Sunrise"},{"id":656,"text":"Wallpoet"},{"id":657,"text":"Walter Turncoat"},{"id":658,"text":"Warnes"},{"id":659,"text":"Wellfleet"},{"id":660,"text":"Wendy One"},{"id":661,"text":"Wire One"},{"id":662,"text":"Yanone Kaffeesatz"},{"id":663,"text":"Yellowtail"},{"id":664,"text":"Yeseva One"},{"id":665,"text":"Yesteryear"},{"id":666,"text":"Zeyada"}]' );
		
		foreach( $fonts as $font_id => $font ){
			$this->settings['fonts'][ $font->text ] = $font->text;
			
			if( $font_id > 9 ){
				$this->google_fonts[ $font->text ] = $font->text;
			}
		}
	}
	/**
	 * Shortcode icon_link admin interface
	 */
	public function build_shortcode_editor_options(){
		
		$this->apply_settings();
		
		$html = '';
		
		$source = array();
		if( otw_post( 'shortcode_object', false, array(), 'json' ) ){
			$source = otw_post( 'shortcode_object', array(), array(), 'json' );
		}
		
		$labels = $this->settings['default_labels'];
		
		if( isset( $source['otw-shortcode-element-labels'] ) ){
			$labels = $source['otw-shortcode-element-labels'];
		}
		
		$html .= OTW_Form::active_elements( array( 'id' => 'otw-shortcode-element-items', 'label_active_elements' => $this->get_label( 'Active Elements' ), 'label_inactive_elements' => $this->get_label( 'Inactive Elements' ), 'description' => $this->get_label( 'Drag & drop the items that you\'d like to show in the Active Elements area on the left. Arrange them however you want to see them.' ), 'items' => $this->settings['items'], 'value' => $this->settings['default_items'], 'parse' => $source ) );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-type', 'label' => $this->get_label( 'Meta Type' ), 'description' => $this->get_label( 'Choose between horizontal and vertical meta style.' ), 'parse' => $source, 'options' => $this->settings['types'], 'value' => $this->settings['default_types'] ) );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-separators', 'label' => $this->get_label( 'Meta Separators' ), 'description' => $this->get_label( 'Enables the separators between the meta elements' ), 'parse' => $source, 'options' => $this->settings['separators'], 'value' => $this->settings['default_separators'] ) );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-labels', 'label' => $this->get_label( 'Meta Labels' ), 'description' => $this->get_label( 'Choose to display Labels, Icons or No Label at all.' ), 'parse' => $source, 'options' => $this->settings['labels'], 'value' => $this->settings['default_labels'], 'data-reload' => '1' ) );
		
		switch( $labels ){
			case 'icons':
					$html .= '<div class="otw-form-control"><span class="otw-form-hint">'.$this->get_label( 'You can change the default icons with any of the Font Awesome icons. For example if you want to display <a href="http://fontawesome.io/icon/comments-o/" target="_blank">http://fontawesome.io/icon/comments-o/</a> you need to enter fa-comments-o in any of the fields bellow.').'</span></div>';
					
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-author_icon', 'label' => $this->get_label( 'Author Icon' ), 'description' => $this->get_label( 'If empty "fa-user" will be used.' ), 'parse' => $source )  );
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-date_icon', 'label' => $this->get_label( 'Date Icon' ), 'description' => $this->get_label( 'If empty "fa-calendar-check-o" will be used.' ), 'parse' => $source )  );
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-category_icon', 'label' => $this->get_label( 'Category Icon' ), 'description' => $this->get_label( 'If empty "fa-folder-open-o" will be used.' ), 'parse' => $source )  );
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-tags_icon', 'label' => $this->get_label( 'Tags Icon' ), 'description' => $this->get_label( 'If empty "fa-tags" will be used.' ), 'parse' => $source )  );
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-comments_icon', 'label' => $this->get_label( 'Comments Icon' ), 'description' => $this->get_label( 'If empty "fa-comments-o" will be used.' ), 'parse' => $source )  );
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-views_icon', 'label' => $this->get_label( 'Views Icon' ), 'description' => $this->get_label( 'If empty "fa-eye" will be used.' ), 'parse' => $source )  );
					
				break;
			case 'none':
				break;
			case 'labels':
			default:
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-author_text', 'label' => $this->get_label( 'Author Label Text' ), 'description' => $this->get_label( 'If empty "By:" will be displayed.' ), 'parse' => $source )  );
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-date_text', 'label' => $this->get_label( 'Date Label Text' ), 'description' => $this->get_label( 'If empty "Posted:" will be displayed.' ), 'parse' => $source )  );
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-category_text', 'label' => $this->get_label( 'Category Label Text' ), 'description' => $this->get_label( 'If empty "Category:" will be displayed.' ), 'parse' => $source )  );
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-tags_text', 'label' => $this->get_label( 'Tags Label Text' ), 'description' => $this->get_label( 'If empty "Tags:" will be displayed.' ), 'parse' => $source )  );
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-comments_text', 'label' => $this->get_label( 'Comments Label Text' ), 'description' => $this->get_label( 'If empty "Comments:" will be displayed.' ), 'parse' => $source )  );
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-views_text', 'label' => $this->get_label( 'Views Label Text' ), 'description' => $this->get_label( 'If empty "Visits:" will be displayed.' ), 'parse' => $source )  );
				break;
		}
		
		return $html;
	}
	
	/**
	 * Shortcode admin interface custom options
	 */
	public function build_shortcode_editor_custom_options(){
		
		$html = '';
		
		$source = array();
		if( otw_post( 'shortcode_object', false, array(), 'json' ) ){
			$source = otw_post( 'shortcode_object', array(), array(), 'json' );
		}
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-label_size', 'label' => $this->get_label( 'Font Size' ), 'description' => $this->get_label( 'The font size.' ), 'parse' => $source, 'options' => $this->settings['sizes'], 'value' => $this->settings['default_size'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-label_style', 'label' => $this->get_label( 'Font Style' ), 'description' => $this->get_label( 'The font style.' ), 'parse' => $source, 'options' => $this->settings['styles'], 'value' => $this->settings['default_style'] )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-label_font', 'label' => $this->get_label( 'Font Family' ), 'description' => $this->get_label( 'The font family.' ), 'parse' => $source, 'options' => $this->settings['fonts'], 'value' => $this->settings['default_font'] )  );
		
		$html .= OTW_Form::color_picker( array( 'id' => 'otw-shortcode-element-label_color', 'label' => $this->get_label( 'Font Color' ), 'description' => $this->get_label( 'Choose a custom color.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-alignment', 'label' => $this->get_label( 'Meta Alignment' ), 'description' => $this->get_label( 'Choose the meta alignment.' ), 'parse' => $source, 'options' => $this->settings['alignments'], 'value' => $this->settings['default_alignment'] )  );
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-css_class', 'label' => $this->get_label( 'CSS Class' ), 'description' => $this->get_label( 'If you\'d like to style this element separately enter a name here. A CSS class with this name will be available for you to style this particular element..' ), 'parse' => $source )  );
		
		return $html;
	}
	
	/** build icon link shortcode
	 *
	 *  @param array
	 *  @return string
	 */
	public function build_shortcode_code( $attributes ){
		
		$code = '';
		
		if( !$this->has_error ){
		
			$code = '[otw_shortcode_post_item_meta';
			
			$code .= $this->format_attribute( 'items', 'items', $attributes );
			
			$code .= $this->format_attribute( 'type', 'type', $attributes );
			
			$code .= $this->format_attribute( 'labels', 'labels', $attributes );
			
			$code .= $this->format_attribute( 'separators', 'separators', $attributes );
			
			$labels = $this->format_attribute( '', 'labels', $attributes );
			
			switch( $labels ){
				case 'icons':
						foreach( $this->settings['items'] as $item => $item_name ){
							$code .= $this->format_attribute( $item.'_icon', $item.'_icon', $attributes, false, '', true );
						}
					break;
				case 'none':
					break;
				case 'labels':
				default:
						foreach( $this->settings['items'] as $item => $item_name ){
							$code .= $this->format_attribute( $item.'_text', $item.'_text', $attributes, false, '', true );
						}
					break;
			}
			
			$code .= $this->format_attribute( 'label_size', 'label_size', $attributes );
			
			$code .= $this->format_attribute( 'label_style', 'label_style', $attributes );
			
			$code .= $this->format_attribute( 'label_font', 'label_font', $attributes );
			
			$code .= $this->format_attribute( 'label_color', 'label_color', $attributes );
			
			$code .= $this->format_attribute( 'alignment', 'alignment', $attributes );
			
			$code .= $this->format_attribute( 'css_class', 'css_class', $attributes, false, '', true  );
			
			$code .= ']';
			
			$code .= '[/otw_shortcode_post_item_meta]';
		}
		
		return $code;
	}
	
	/**
	 * Process shortcode icon link
	 */
	public function display_shortcode( $attributes, $content ){
		
		$html = '';
		
		$post_item_id = $this->_get_post_item_id( $attributes );
		
		if( is_admin() ){
			$html = '<img src="'.$this->component_url.'images/sidebars-icon-placeholder.png'.'" alt=""/>';
		}else{
			$html = '';
			
			if( $post_item_id ){
			
				global $otw_post_items_data;
				
				if( is_array( $otw_post_items_data ) && isset( $otw_post_items_data[ $post_item_id ] ) && isset( $otw_post_items_data[ $post_item_id ]['data'] ) ){
					
					$this->shortcode_id = $this->format_attribute( '', 'ssid', $attributes, false, '' );
					
					$otw_post_items_data[ $post_item_id ]['dispatcher']->view_data['settings']['otw_pct_meta_id'] = $this->format_attribute( '', 'ssid', $attributes, false, '' );
					
					$otw_post_items_data[ $post_item_id ]['dispatcher']->view_data['settings']['otw_pct_meta_icons'] = $this->format_attribute( '', 'labels', $attributes, false, '' );
					
					$otw_post_items_data[ $post_item_id ]['dispatcher']->view_data['settings']['otw_pct_meta_type_align'] = $this->format_attribute( '', 'type', $attributes, false, '' );
					
					foreach( $this->settings['items'] as $item => $item_name ){
						
						$otw_post_items_data[ $post_item_id ]['dispatcher']->view_data['settings']['otw_pct_meta_'.$item.'_text']  = $this->format_attribute( '', $item.'_text', $attributes, false, '' );
						$otw_post_items_data[ $post_item_id ]['dispatcher']->view_data['settings']['otw_pct_meta_'.$item.'_icon']  = $this->format_attribute( '', $item.'_icon', $attributes, false, '' );
					}
					
					$otw_post_items_data[ $post_item_id ]['dispatcher']->view_data['settings']['otw_pct_meta_items'] = $this->format_attribute( '', 'items', $attributes, false, '' );
					
					$otw_post_items_data[ $post_item_id ]['dispatcher']->view_data['settings']['otw_pct_meta_separators'] = $this->format_attribute( '', 'separators', $attributes, false, '' );
					
					$otw_post_items_data[ $post_item_id ]['dispatcher']->view_data['settings']['otw_pct_meta_css_class'] = $this->format_attribute( '', 'css_class', $attributes, false, '' );
					
					if( $this->shortcode_id ){
						// build custom style
						$css_string = '';
						$open_rule = '';
						
						if( $att_value = $this->format_attribute( '', 'alignment', $attributes, false, '' ) ){
							switch( $att_value ){
								
								case 'right':
								case 'left':
										$open_rule .= 'float: '.$att_value.' !important;';
									break;
								case 'center':
										$open_rule .= 'float: none !important;';
										$open_rule .= 'text-align: '.$att_value.' !important;';
										$open_rule .= 'margin: 0 auto !important;';
									break;
							}
						}
						
						if( $open_rule ){
							$css_string .= '#'.$this->shortcode_id.'{'.$open_rule.'}';
							$open_rule = '';
						}
						
						if( $att_value = $this->format_attribute( '', 'label_size', $attributes, false, '' ) ){
							
							$open_rule .= ' font-size: '.$att_value.'px !important;';
						}
						
						if( $att_value = $this->format_attribute( '', 'label_style', $attributes, false, '' ) ){
							
							switch( $att_value ){
								
								case 'bold':
										$open_rule .= ' font-weight: '.$att_value.' !important;';
									break;
								case 'italic':
										$open_rule .= ' font-style: '.$att_value.' !important;';
									break;
								case 'bold_italic':
										$open_rule .= ' font-weight: bold !important;';
										$open_rule .= ' font-style: italic !important;';
									break;
							}
						}
						
						if( $att_value = $this->format_attribute( '', 'label_font', $attributes, false, '' ) ){
							
							if( in_array( $att_value, $this->google_fonts ) ){
								
								$this->fonts_to_include[ $att_value ] = $att_value;
							}
							
							$open_rule .= ' font-family: '.$att_value.' !important;';
						}
						
						if( $open_rule ){
							$css_string .= '#'.$this->shortcode_id.'>div span{'.$open_rule.'}';
							$css_string .= '#'.$this->shortcode_id.'>div a{'.$open_rule.'}';
							$open_rule = '';
						}
						
						if( $att_value = $this->format_attribute( '', 'label_color', $attributes, false, '' ) ){
							
							$open_rule .= ' color: '.$att_value.' !important;';
						}
						
						if( $open_rule ){
							$css_string .= '#'.$this->shortcode_id.'>div span{'.$open_rule.'}';
							$open_rule = '';
						}
						
						$this->custom_styles[] = $css_string;
						
						add_action( 'wp_footer', array( $this, 'otw_shortcode_custom_styles' ) );
					}
					
					$html .= $otw_post_items_data[ $post_item_id ]['dispatcher']->buildPostMetaItems( $otw_post_items_data[ $post_item_id ]['data'] );
				}
			}
		}
		
		return $this->format_shortcode_output( $html );
	}
	
	function otw_shortcode_custom_styles(){
	
		if( count( $this->fonts_to_include ) ){
			
			$url = '//fonts.googleapis.com/css?family='.urlencode( implode( '|', $this->fonts_to_include ) ).'&variant=italic:bold';
			
			wp_enqueue_style('otw-smi-googlefonts',$url, null, null);
		}
	
		echo '<style type="text/css">'.implode( ' ', $this->custom_styles ).'</style>';
	}
	
	/**
	 * Return shortcode attributes
	 */
	public function get_shortcode_attributes( $attributes ){
		
		$shortcode_attributes = array();
		
		if( isset( $attributes['item_type'] ) ){
		
			if( isset( $this->settings['item_type_options'][ $attributes['item_type'] ] ) ){
				$shortcode_attributes['iname'] = $this->settings['item_type_options'][ $attributes['item_type'] ];
			}else{
				$shortcode_attributes['iname'] = ucfirst( $attributes['item_type'] );
			}
		}
		
		return $shortcode_attributes;
	}
}
?>