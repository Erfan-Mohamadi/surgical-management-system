<?phpاخبار

namespace Database\Seeders;

use App\Models\Speciality;
use Illuminate\Database\Seeder;

class SpecialitySeeder extends Seeder
{
    public function run()
    {
        Speciality::factory()->count(20)->create();
    }
}
