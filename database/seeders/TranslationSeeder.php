<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private $resultOfKeys = null;
    private $emptyVisitedArray = false;
    private $keysWithValues = array();

    public function run()
    {
        /* fill translations with all store ids */
        $translations = __('responses');
        $visited = array();
        $result = $this->getKeysAndValues($translations, $visited);
        /* Get all store's ids and save key with value for each id in translations table */ 
        $stores = Store::query()->get('id');
        foreach($stores as $store)
        {
            foreach($result as $key => $value)
                $this->saveInTranslations($key, $value, $store->id);
        }
    }

    public function getKeysAndValues($arr, $visited)
    {   
        foreach ($arr as $key => $value)
            {              
                if ($this->emptyVisitedArray == true)
                {
                    while (count($visited) > 0)
                    {
                        array_pop($visited);
                    }
                    $this->emptyVisitedArray = false;
                }
               
                $len = is_array($value)? (count($value)) : (0);
                
                if($this->resultOfKeys)
                {
                    array_push($this->resultOfKeys, [$key, $len]);
                    $visited = $this->resultOfKeys;
                } else
                {
                    array_push($visited, [$key, $len]);
                }

                if (!is_array($value))
                {    
                    $keysArr = array();
                    for ($i = 0; $i < count($visited); $i++)
                    {
                        array_push($keysArr, $visited[$i][0]);
                    }

                    $keyStr = implode('.', $keysArr);
                    if (count($this->keysWithValues) > 0)
                        $this->keysWithValues = array_merge($this->keysWithValues, [$keyStr => $value]);
                    else
                        $this->keysWithValues[$keyStr] = $value;

                    $visited[count($visited) - 1][1] = 0;
                                     
                    if ($visited)
                    {
                        $this->resultOfKeys = $this->removeVisitedItems($visited);
                    }
                    
                    if ($this->resultOfKeys == null)
                    {
                        $this->emptyVisitedArray = true;
                    }                                             
                }
                else
                {        
                    $this->getKeysAndValues($value, $visited); 
                }
            }  
            
            return $this->keysWithValues;
    }

    public function removeVisitedItems($visited)
    {
        $i = count($visited) - 1;
        while($i >= 0)
        {
            if ($visited[$i][1] == 0)
            {
                array_pop($visited);
                if ($i > 0)
                    $visited[$i - 1][1] -= 1;
            }
            $i--;
        }

        return $visited;
   
    }
    
    public function saveInTranslations($key, $value, $store_id)
    {
        DB::table('translations')->insert([
            'key' => $key,
            'value' => $value,
            'store_id' => $store_id,
            'created_at' => now()
        ]);
    }
}
