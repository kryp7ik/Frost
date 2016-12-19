<?php

use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recipeArray = [];
        foreach ($this->getRecipeArray() as $recipe) {
            $flavorData = explode(',', $recipe);
            $flavorName = str_replace('_', ' ', $flavorData[0]);
            $recipeArray[] = [
                'name' => trim($flavorName),
                'sku' => $flavorData[1],
                'active' => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ];
        }
        DB::table('recipes')->insert($recipeArray);
    }

    private function getRecipeArray() {
        $csv = 'Angel\'s_Milk,E001
                Apache,E002
                Aphrodite,E003
                Apple_Lemonade,E004
                Apple_Nectar,E005
                Apple_Pie,E006
                Asgard,E007
                Ashla,E008
                Atlantis,E009
                Atomic,E010
                Baja_Dew,E011
                Banana_Candy,E012
                Banana_Split,E013
                Bee_Sting,E014
                BeetleJuice,E015
                Berry_Breeze,E016
                Berry_Crunch,E017
                Black_Cherry,E018
                Black_Coffee,E019
                Black_Honey_Tobacco,E020
                Blackberry,E021
                Blam_Cracker,E022
                Bloo,E023
                Blood_Orange,E024
                Blue_Dew,E025
                Blue_Lightning,E026
                Blue_Lightning_Candy,E027
                Blue_Lightning_Watermelon,E028
                Blue_Racer,E029
                Blue_Razzleberry,E030
                Blue_Trucker,E031
                Blueberry,E032
                Blueberry_Custard,E033
                Blueberry_Lemonade,E034
                Bourbon,E035
                ButterScotch_Candy,E036
                Calypso,E037
                Caramel_Apple,E038
                Caramel_Swirl,E039
                Carnival_of_Madness,E040
                Cerealusly,E041
                Chai_Tea,E042
                Citrus_Punch,E043
                Cloud_Cream,E044
                Coconut,E045
                Coconut_Milk,E046
                Cosmic_Sun,E047
                Cotton_Candy,E048
                Cran_Grape,E049
                Cryo,E050
                Crystal_Clear,E051
                Cumulust,E052
                Deathstar,E053
                Deep_Freeze,E054
                Dew,E055
                Doubled_Bubble,E056
                Dr._Jolt_Cola,E057
                Dragon_Colada,E058
                Dragon\'s_Blood,E059
                Eclipse,E060
                Ecstasy,E061
                Ectoplasm,E062
                Emerald_Forest,E063
                Enigma,E064
                Ethereal,E065
                Fire_&_Ice,E066
                Firecracker,E067
                Firestarter,E068
                Forbidden_Fruit,E069
                Fruit_Orgy,E070
                Fudged_Up,E071
                Grape_Ape,E072
                Grape_Bubble,E073
                Grape_Candy,E074
                Grape_Rancher,E075
                Grapple,E076
                Green_Rancher,E077
                Half_Western,E078
                Hawaiian_Drink,E079
                Hell\'s_Bells,E080
                Honey_Bear,E081
                Honey_Peary,E082
                Horchata,E083
                Hot_Fuzz,E084
                Ice_Spear,E085
                Iced_Espresso,E086
                Jamaican_Jungle,E087
                JaMocha_Shake,E088
                Johnny_Appleseed,E089
                Jolt_Cola,E090
                Jolt_Drink,E091
                Jubilee,E092
                Jungle_Juice,E093
                Key_Lime_Pie,E094
                Kiwi_Melons,E095
                Kiwi_Strawberry,E096
                Kong’s_Revenge,E097
                Kryptonic,E098
                Le_Custard,E099
                Lemonade,E100
                Lemonberry_Rush,E101
                Luau,E102
                Lucid_Dream,E103
                Lucky_Clover,E104
                Medusa\'s_Love,E105
                Melon_Balla,E106
                Miami_Vice,E107
                Michigan\'s_Best,E108
                Midnight_Rain,E109
                Mint_Chocolate_Chip,E110
                Minty_Fresh,E111
                Mystical,E112
                Nature\'s_Nectar,E113
                Nemesis,E114
                Nexus,E115
                Orange_Cream,E116
                Orange_Hateraid,E117
                Orange_Nemesis,E118
                Paradox,E119
                Peach,E120
                Peach_Mango,E121
                Peach_Rings,E122
                Peaches_N_Cream,E123
                Peanut_Butter_Cup,E124
                Peppermint,E125
                Pina_Colada,E126
                Pineapple_Express,E127
                Pink_Lemonade,E128
                Pink_Monkey,E129
                Pink_Star,E130
                Pixie_Dust,E131
                Plentiful_Pear,E132
                Pomegranate,E133
                Princess_Peach,E134
                Purple_Drank,E135
                Purple_Sticky_Punch,E136
                Quantum,E137
                Radioactive,E138
                Rainbow_Drops,E139
                Raspberry,E140
                Razznilla,E141
                Red_Blend,E142
                Red_Ruby,E143
                Ring_of_Fruit,E144
                Ripe_Strawberry,E145
                Rising_Sun,E146
                Roid_Rage,E147
                Rollups,E148
                Root_Beer_Barrels,E149
                RY4,E150
                Serenity,E151
                Shamrock_Shake,E152
                Shockwave,E153
                Silver_Lining,E154
                Smarties,E155
                Smores,E156
                Snake_Oil,E157
                Snocone,E158
                Snowberry_Kiss,E159
                Snowberry_Vanilla_Custard,E160
                Sour_Apple,E161
                Space_Jam,E162
                Stranilla,E163
                Strawbana,E164
                Strawberries_&_Cream,E165
                Strawberry,E166
                Strawberry_Bubble,E167
                Strawberry_Cheesecake,E168
                Strawberry_Cupcake,E169
                Strawmelon_Splash,E170
                Sugar_Smax,E171
                Summer_Nights,E172
                Supernova,E173
                Supervape,E174
                Swedish_Fish,E175
                Sweet_Melons,E176
                Sweet_Mocha,E177
                Sweet_Raspberry,E178
                Sweet_Strawberry,E179
                The_Trucker,E180
                The_Weak_Sauce,E181
                Thunderbolt,E182
                Tiger\'s_Blood,E183
                Tropical_Thunder,E184
                Turkish_Blend,E185
                Twisted_Coconut,E186
                Valhalla,E187
                Vanilla,E188
                Vanilla_Custard,E189
                Venom,E190
                Waterloupe,E191
                Watermelon,E192
                Watermelon_Bubble,E193
                Watermelon_Lemonade,E194
                Western_Blend,E195
                Whipped_Cherry,E196
                White_Lightning,E197
                Wild_Cherry,E198
                Wintergreen,E199
                Zeus_Juice,E200';
        return explode("\n", $csv);


    }
}
