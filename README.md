<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

    1)  composer create-project laravel/laravel KonyvtarBackend
    2)  env-ben az adatbázis átírása -> vizsga-2022-14s-wip-db
    
    3)  php artisan make:model -msfcRr Rental   
        --- Model, Factory, Migration, Seeder, Request, Controller ---
    4)  php artisan make:model -cRr Book
        --- Model, Request, Controller ---
    
    5)  Model kitöltése:  
    protected $table = 'books';

    protected $fillable=['title', 'author', 'publish_year', 'page_count'];
            
    protected $visible=['id', 'title', 'author', 'publish_year', 'page_count'];

    5)  Migration kitöltése: (up-ba, pl.:)
            $table->foreignIdFor(Book::class)->constrained();
            $table->date('start_date');

    6)  Factory kitöltése: (a definition returnba)
            "book_id"=>$this->faker->unique()->numberBetween(1,50),
            'start_date' => Carbon::now()->subDays($this->faker->numberBetween(7, 15)),
            'end_date'=> Carbon::now()->addDays($this->faker->numberBetween(7, 15)),

    7)  Seeder kitöltése: (RentalSeeder run-ba, pl.:)
            Rental::factory(15)->create();
                           (DatabaseSeeder run-ba, pl.:)
            $this->call(RentalSeeder::class);

    8)  Request kitöltése: (rules-ba, pls.:)
            'title' => 'string|required|max:255',
            'author' => 'string|required|max:255',
            'publish_year' => 'integer|required',
            'page_count' => 'integer|min:1|required',
        Az authorise-t MINDIG állítsd át true-ra!!!
    

--------------- php artisan migrate:refresh --seed ----------------


    10)  Controller kitöltése: App\Http\Controllers-ben 

    public function index()
    {
        $books=Book::all();
        return  response($books);
    }

    public function store(Request $request)
    {
        $validator= Validator::make($request->all(), (new StoreBookRequest())->rules());
        if($validator->fails()){
            $errormsg="";
            foreach ($validator->errors()->all() as $error) {
                $errormsg .= $error . " ";
            }
            $errormsg = trim($errormsg);
            return response()->json($errormsg, 400);
 
        }           
        $book=new Book();
        $book->fill($request->all());
        $book->save();

        return response()->json($book, 201);
    }

    public function show(int $id)
    {
        $book = Book::find($id);
        if (is_null($book)) {
            return response()->json(["message" => "A megadott azonosítóval nem található könyv."], 404);
        }
        return response()->json($book);
    }

    public function update(Request $request, int $id)
    {
        if ($request->isMethod('PUT')) {
            $validator = Validator::make($request->all(), (new Book())->rules());
            if ($validator->fails()) {
                $errormsg = "";
                foreach ($validator->errors()->all() as $error) {
                    $errormsg .= $error . " ";
                }
                $errormsg = trim($errormsg);
                return response()->json($errormsg, 400);
            }
        }
        $book = Book::find($id);
        if (is_null($book)) {
            return response()->json(["message" => "A megadott azonosítóval nem található könyv."], 404);
        }
        $book->fill($request->all());
        $book->save();
        return response()->json($book, 200);
    }

    public function destroy(int $id)
    {
        $book = Book::find($id);
        if (is_null($book)) {
            return response()->json(["message" => "A megadott azonosítóval nem található könyv."], 404);
        }
        Book::destroy($id);
        return response()->noContent();
    }

----nem jó--- 

    public function rentForAWeek(Request $request, int $id)
    {
        $book = Book::find($id);
        if (is_null($book)) {
            return response()->json(["message" => "A megadott azonosítóval nem található könyv."], 404);
        }
        $rents=Rental::where('book_id', $book->id)->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())->count();
        if ($rents!=0) {
            return response()->json(["message" => "A megadott azonosítóval rendelkező könyv már foglalt."], 409);
        }
        $rent=new Rental();
        $rent->book_id=$book->id;
        $rent->start_date=Carbon::now();
        $rent->end_date=Carbon::now()->addDays(7);
        $rent->save();
        return response()->json($rent);
     }


    11) Api végpontok: (routes/api.php legalulra, pl.:)

Route::apiResource("/books",BookController::class);

//Route::post('/books/{id}/rent', BookController::class, 'rentForAWeek');



### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
