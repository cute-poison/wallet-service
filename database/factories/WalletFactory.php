<?php

namespace Database\Factories;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    /**  
     * The name of the factory's corresponding model.  
     *  
     * @var string  
     */  
    protected $model = Wallet::class;  

    /**  
     * Define the model's default state.  
     *  
     * @return array  
     */  
    public function definition()  
    {  
        return [  
            // If you want to auto-create a user, this line is fine.
            'user_id'  => User::factory(),

            // Or if you prefer the seeded user, you’ll override this in your seeder.
            //'user_id'  => 1,

            'currency' => $this->faker->currencyCode,  
            'balance'  => $this->faker->randomFloat(2, 0, 1000),  
        ];  
    }  
}
