<?php

namespace Database\Seeders;

use App\Models\CustomerSatisfactionQuestion;
use Illuminate\Database\Seeder;

class CustomerSatisfactionQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [

            /*
             * Sales Representative
             */
            ['question' => 'How satisfied were you with your sales representative?',

                'type' => 'rating',

                'category' => 'salesman',

                'is_required' => true,

                'is_active' => true,

                'sort_order' => 10,],

            ['question' => 'How well did our sales representative understand your needs?',

                'type' => 'rating',

                'category' => 'salesman',

                'is_required' => true,

                'is_active' => true,

                'sort_order' => 20,],


            /*
             * Products
             */
            ['question' => 'How satisfied are you with our products?',

                'type' => 'rating',

                'category' => 'product',

                'is_required' => true,

                'is_active' => true,

                'sort_order' => 30,],


            /*
             * Meeting
             */
            ['question' => 'How useful was the meeting?',

                'type' => 'rating',

                'category' => 'meeting',

                'is_required' => true,

                'is_active' => true,

                'sort_order' => 40,],


            /*
             * Overall Satisfaction
             */
            ['question' => 'Overall, how satisfied are you with your experience?',

                'type' => 'rating',

                'category' => 'general',

                'is_required' => true,

                'is_active' => true,

                'sort_order' => 50,],


            /*
             * Comments
             */
            ['question' => 'Is there anything we can improve?',

                'type' => 'textarea',

                'category' => 'general',

                'is_required' => false,

                'is_active' => true,

                'sort_order' => 60,],

            ['question' => 'Is there anything else you would like to share with us?',

                'type' => 'textarea',

                'category' => 'general',

                'is_required' => false,

                'is_active' => true,

                'sort_order' => 70,],];


        foreach ($questions as $question) {

            CustomerSatisfactionQuestion::updateOrCreate(['question' => $question['question'],], $question);

        }
    }
}
