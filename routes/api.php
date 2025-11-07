use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherApiController;

Route::get('/weather/alerts/raw', [WeatherApiController::class, 'alertsRaw']); // GeoJSON passthrough
Route::get('/weather/alerts', [WeatherApiController::class, 'alerts']); // normalized
Route::get('/weather/forecast', [WeatherApiController::class, 'forecast']); // ?city=Wichita