<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsAndAnswersSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        // question => [correct answer, wrong1, wrong2, wrong3]
        $easyQuestions = [
            'What is the capital of France?' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'How many legs does a spider have?' => ['8', '6', '10', '12'],
            'What color do you get when you mix red and white?' => ['Pink', 'Purple', 'Brown', 'Orange'],
            'What is the name of the fairy in Peter Pan?' => ['Tinker Bell', 'Wendy', 'Ariel', 'Cinderella'],
            'Which planet is closest to the Sun?' => ['Mercury', 'Venus', 'Earth', 'Mars'],
            'What fruit is known for keeping doctors away?' => ['Apple', 'Banana', 'Grapes', 'Orange'],
            'What is H2O more commonly known as?' => ['Water', 'Oxygen', 'Hydrogen', 'Salt'],
            'Which animal is known as man’s best friend?' => ['Dog', 'Cat', 'Horse', 'Parrot'],
            'What currency is used in the United States?' => ['Dollar', 'Euro', 'Pound', 'Peso'],
            'Which sport uses a racket, net, and shuttlecock?' => ['Badminton', 'Tennis', 'Volleyball', 'Squash'],
            'How many days are there in a leap year?' => ['366', '365', '364', '360'],
            'What do bees make?' => ['Honey', 'Wax', 'Milk', 'Juice'],
            'What is the opposite of hot?' => ['Cold', 'Warm', 'Fire', 'Steam'],
            'Which month comes after August?' => ['September', 'October', 'July', 'June'],
            'How many continents are there?' => ['7', '6', '5', '8'],
            'What is the main ingredient in guacamole?' => ['Avocado', 'Tomato', 'Onion', 'Pepper'],
            'Which sea creature has eight legs?' => ['Octopus', 'Crab', 'Jellyfish', 'Starfish'],
            'Which day comes after Friday?' => ['Saturday', 'Thursday', 'Sunday', 'Monday'],
            'What is the boiling point of water in Celsius?' => ['100', '90', '80', '70'],
            'What is the opposite of up?' => ['Down', 'Over', 'Above', 'Forward'],
        ];

        $moderateQuestions = [
            'In which year did the Titanic sink?' => ['1912', '1915', '1905', '1920'],
            'What’s the longest river in the world?' => ['Nile', 'Amazon', 'Yangtze', 'Mississippi'],
            'Who painted the Mona Lisa?' => ['Leonardo da Vinci', 'Michelangelo', 'Van Gogh', 'Picasso'],
            'What is the smallest prime number?' => ['2', '1', '0', '3'],
            'What is the capital city of Canada?' => ['Ottawa', 'Toronto', 'Vancouver', 'Montreal'],
            'How many hearts does an octopus have?' => ['3', '1', '2', '4'],
            'What is the hardest natural substance on Earth?' => ['Diamond', 'Steel', 'Granite', 'Quartz'],
            'Which gas do plants absorb from the atmosphere?' => ['Carbon dioxide', 'Oxygen', 'Hydrogen', 'Nitrogen'],
            'Which planet is famous for its rings?' => ['Saturn', 'Mars', 'Neptune', 'Jupiter'],
            'Who invented the telephone?' => ['Alexander Graham Bell', 'Thomas Edison', 'Nikola Tesla', 'Isaac Newton'],
            'Which country gifted the Statue of Liberty to the US?' => ['France', 'United Kingdom', 'Germany', 'Spain'],
            'What is the square root of 144?' => ['12', '11', '14', '10'],
            'Which instrument has keys, pedals, and strings?' => ['Piano', 'Violin', 'Guitar', 'Flute'],
            'Who discovered penicillin?' => ['Alexander Fleming', 'Louis Pasteur', 'Marie Curie', 'Gregor Mendel'],
            'How many players are on a soccer team (on the field)?' => ['11', '10', '9', '12'],
            'What’s the capital of Australia?' => ['Canberra', 'Sydney', 'Melbourne', 'Perth'],
            'What metal is liquid at room temperature?' => ['Mercury', 'Lead', 'Aluminum', 'Copper'],
            'Which element has the chemical symbol “O”?' => ['Oxygen', 'Gold', 'Osmium', 'Zinc'],
            'What’s the term for animals that eat both plants and meat?' => ['Omnivores', 'Carnivores', 'Herbivores', 'Insectivores'],
            'What’s the name of the galaxy that contains our solar system?' => ['Milky Way', 'Andromeda', 'Whirlpool', 'Triangulum'],
            'Which country has the largest population in the world?' => ['China', 'India', 'USA', 'Indonesia'],
            'Who is known as the “Father of Computers”?' => ['Charles Babbage', 'Alan Turing', 'Bill Gates', 'Steve Jobs'],
            'What is the main language spoken in Brazil?' => ['Portuguese', 'Spanish', 'English', 'French'],
            'What is the chemical formula for table salt?' => ['NaCl', 'KCl', 'H2O', 'CO2'],
            'Which famous scientist developed the theory of relativity?' => ['Albert Einstein', 'Isaac Newton', 'Galileo Galilei', 'Stephen Hawking'],
            'Which continent is the Sahara Desert located in?' => ['Africa', 'Asia', 'Australia', 'South America'],
            'What is the freezing point of water in Fahrenheit?' => ['32', '0', '100', '212'],
            'Which instrument measures atmospheric pressure?' => ['Barometer', 'Thermometer', 'Hygrometer', 'Altimeter'],
            'How many sides does a hexagon have?' => ['6', '5', '7', '8'],
            'Who wrote the novel “1984”?' => ['George Orwell', 'Aldous Huxley', 'Ernest Hemingway', 'F. Scott Fitzgerald'],
            'What’s the main ingredient in traditional Japanese miso soup?' => ['Soybean paste', 'Rice noodles', 'Fish stock', 'Seaweed'],
            'Which planet is known as the “Morning Star”?' => ['Venus', 'Mercury', 'Mars', 'Jupiter'],
            'What is the capital of Norway?' => ['Oslo', 'Stockholm', 'Copenhagen', 'Helsinki'],
            'In what country did the Olympic Games originate?' => ['Greece', 'Italy', 'France', 'Egypt'],
            'How many bones are in the adult human body?' => ['206', '201', '210', '205'],
            'What is the chemical symbol for gold?' => ['Au', 'Ag', 'Gd', 'Go'],
            'Which U.S. state is known as the Sunshine State?' => ['Florida', 'California', 'Arizona', 'Texas'],
            'What’s the name of Sherlock Holmes’ assistant?' => ['Dr. Watson', 'Inspector Lestrade', 'Mycroft Holmes', 'Mrs. Hudson'],
            'What is the tallest mountain in the world?' => ['Mount Everest', 'K2', 'Makalu', 'Lhotse'],
            'Which vitamin is produced when a person is exposed to sunlight?' => ['Vitamin D', 'Vitamin A', 'Vitamin B12', 'Vitamin C'],
        ];

        $hardQuestions = [
            'What is the heaviest naturally occurring element?' => ['Uranium', 'Osmium', 'Plutonium', 'Lead'],
            'In what year was the first programmable computer invented?' => ['1936', '1941', '1920', '1950'],
            'What is the Schrödinger equation used to describe?' => ['Quantum state evolution', 'Heat transfer', 'Fluid dynamics', 'Gravitational force'],
            'Which philosopher wrote “Being and Time”?' => ['Martin Heidegger', 'Friedrich Nietzsche', 'Immanuel Kant', 'Jean-Paul Sartre'],
            'Who was the first emperor of China?' => ['Qin Shi Huang', 'Liu Bang', 'Kublai Khan', 'Sun Tzu'],
            'What language family does Basque belong to?' => ['It is a language isolate', 'Romance', 'Slavic', 'Germanic'],
            'Which scientist first proposed continental drift?' => ['Alfred Wegener', 'Charles Darwin', 'James Hutton', 'Isaac Newton'],
            'What is the capital of Bhutan?' => ['Thimphu', 'Kathmandu', 'Dhaka', 'Vientiane'],
            'What’s the longest bone in the human body?' => ['Femur', 'Tibia', 'Humerus', 'Spine'],
            'In computer science, what does NP stand for in NP-complete?' => ['Nondeterministic Polynomial time', 'Non-Primary', 'Network Processed', 'Negative Permutation'],
            'What is the SI unit of electric capacitance?' => ['Farad', 'Henry', 'Ohm', 'Ampere'],
            'Who painted “The School of Athens”?' => ['Raphael', 'Da Vinci', 'Caravaggio', 'Titian'],
            'Which country is home to the ancient city of Petra?' => ['Jordan', 'Egypt', 'Iran', 'Turkey'],
            'Which novel begins with the line “Call me Ishmael”?' => ['Moby Dick', 'The Old Man and the Sea', 'Treasure Island', 'Heart of Darkness'],
            'What’s the mathematical name for a shape with 12 sides?' => ['Dodecagon', 'Decagon', 'Tetradecagon', 'Octadecagon'],
            'What’s the currency of Mongolia?' => ['Tögrög', 'Riel', 'Dong', 'Taka'],
            'Which metal has the highest melting point?' => ['Tungsten', 'Iron', 'Titanium', 'Nickel'],
            'What’s the name of the paradox in which a cat is simultaneously alive and dead?' => ['Schrödinger’s cat', 'Maxwell’s demon', 'Zeno’s paradox', 'Fermi paradox'],
            'Which composer wrote the opera “Tristan und Isolde”?' => ['Richard Wagner', 'Mozart', 'Bach', 'Beethoven'],
            'What is the rarest blood type?' => ['AB negative', 'O negative', 'A negative', 'B positive'],
            'Which battle ended Napoleon’s rule as Emperor of the French?' => ['Battle of Waterloo', 'Battle of Leipzig', 'Battle of Austerlitz', 'Battle of Trafalgar'],
            'Which brain lobe processes visual information?' => ['Occipital', 'Frontal', 'Parietal', 'Temporal'],
            'What is the term for a word that is the same forward and backward?' => ['Palindrome', 'Anagram', 'Synonym', 'Oxymoron'],
            'Which gas has the highest percentage in Earth’s atmosphere?' => ['Nitrogen', 'Oxygen', 'Carbon Dioxide', 'Argon'],
            'Who discovered radioactivity?' => ['Henri Becquerel', 'Marie Curie', 'Wilhelm Röntgen', 'James Clerk Maxwell'],
            'What is the term for animals that are active during twilight?' => ['Crepuscular', 'Nocturnal', 'Diurnal', 'Matutinal'],
            'Which element is represented by the symbol “W”?' => ['Tungsten', 'Wolframium', 'Tin', 'Antimony'],
            'Which Greek mathematician is known for his theorem on right-angled triangles?' => ['Pythagoras', 'Euclid', 'Archimedes', 'Aristotle'],
            'In literature, who created the detective Hercule Poirot?' => ['Agatha Christie', 'Arthur Conan Doyle', 'Dorothy Sayers', 'Raymond Chandler'],
            'What is the approximate value of the golden ratio?' => ['1.618', '3.14', '2.72', '0.707'],
            'Which German city is famous for hosting the annual Oktoberfest?' => ['Munich', 'Berlin', 'Frankfurt', 'Hamburg'],
            'What is the scientific name for the kneecap?' => ['Patella', 'Scapula', 'Clavicle', 'Femur'],
            'What is the most spoken native language in the world?' => ['Mandarin Chinese', 'English', 'Spanish', 'Hindi'],
            'Which philosopher introduced the concept of the categorical imperative?' => ['Immanuel Kant', 'Plato', 'Descartes', 'Spinoza'],
            'Which country was formerly known as Abyssinia?' => ['Ethiopia', 'Sudan', 'Eritrea', 'Somalia'],
            'What is the term for the fear of long words?' => ['Hippopotomonstrosesquipedaliophobia', 'Sesquipedalophobia', 'Linguaphobia', 'Verbophobia'],
            'Who composed “The Planets” orchestral suite?' => ['Gustav Holst', 'Igor Stravinsky', 'Claude Debussy', 'Aaron Copland'],
            'What is the largest internal organ in the human body?' => ['Liver', 'Lungs', 'Heart', 'Kidneys'],
            'In what field was the Nobel Prize first awarded in 1901?' => ['Physics', 'Peace', 'Literature', 'Medicine'],
            'Which country has the most time zones?' => ['France', 'Russia', 'USA', 'Australia'],
            'What is the powerhouse of the cell?' => ['Mitochondria', 'Nucleus', 'Ribosome', 'Golgi apparatus'],
            'Who is the author of “Crime and Punishment”?' => ['Fyodor Dostoevsky', 'Leo Tolstoy', 'Anton Chekhov', 'Vladimir Nabokov'],
            'Which gas is used in electric bulbs?' => ['Argon', 'Oxygen', 'Neon', 'Hydrogen'],
            'Who discovered the circulation of blood in the human body?' => ['William Harvey', 'Andreas Vesalius', 'Galen', 'Hippocrates'],
            'Which British king had six wives?' => ['Henry VIII', 'Edward VI', 'George III', 'Richard III'],
            'What’s the term for a group of crows?' => ['Murder', 'Gaggle', 'Flock', 'Parliament'],
            'What is the scientific study of fungi called?' => ['Mycology', 'Botany', 'Zoology', 'Virology'],
            'Which ancient civilization built Machu Picchu?' => ['Inca', 'Maya', 'Aztec', 'Olmec'],
            'Who formulated the laws of motion and universal gravitation?' => ['Isaac Newton', 'Galileo Galilei', 'Kepler', 'Copernicus'],
            'Which body part has the smallest bones in the human body?' => ['Ear', 'Finger', 'Toe', 'Nose'],
            'What’s the main ingredient in traditional Japanese sake?' => ['Rice', 'Barley', 'Corn', 'Potato'],
            'What is the process of cell division in prokaryotes called?' => ['Binary fission', 'Mitosis', 'Meiosis', 'Conjugation'],
            'What is the capital of Kazakhstan?' => ['Astana', 'Almaty', 'Bishkek', 'Tashkent'],
            'Who wrote “The Divine Comedy”?' => ['Dante Alighieri', 'Geoffrey Chaucer', 'Homer', 'Virgil'],
            'What is the name of the instrument used to measure earthquakes?' => ['Seismograph', 'Barograph', 'Anemometer', 'Hygrometer'],
            'Which civilization invented the cuneiform writing system?' => ['Sumerians', 'Babylonians', 'Egyptians', 'Persians'],
            'What’s the largest desert in the world (by area)?' => ['Antarctic Desert', 'Sahara', 'Arctic Desert', 'Gobi'],
            'What particle carries the electromagnetic force?' => ['Photon', 'Electron', 'Proton', 'Neutrino'],
            'What is the only even prime number?' => ['2', '0', '4', '6'],
            'Which organ is responsible for filtering blood in the body?' => ['Kidneys', 'Lungs', 'Liver', 'Pancreas'],
            'Who discovered penicillin by accident?' => ['Alexander Fleming', 'Louis Pasteur', 'Edward Jenner', 'Joseph Lister'],
            'What’s the largest known star by volume?' => ['UY Scuti', 'Betelgeuse', 'Rigel', 'VY Canis Majoris'],
            'Which hormone regulates blood sugar?' => ['Insulin', 'Adrenaline', 'Estrogen', 'Cortisol'],
            'What is the main gas found in the air we breathe?' => ['Nitrogen', 'Oxygen', 'Carbon Dioxide', 'Helium'],
            'Which artist cut off part of his own ear?' => ['Vincent van Gogh', 'Salvador Dalí', 'Paul Gauguin', 'Claude Monet'],
            'What is the name for molten rock before it erupts?' => ['Magma', 'Lava', 'Basalt', 'Ash'],
            'What is the world’s deepest ocean trench?' => ['Mariana Trench', 'Tonga Trench', 'Java Trench', 'Puerto Rico Trench'],
            'Which U.S. president issued the Emancipation Proclamation?' => ['Abraham Lincoln', 'Thomas Jefferson', 'Ulysses S. Grant', 'Andrew Johnson'],
            'What part of the brain controls balance?' => ['Cerebellum', 'Cerebrum', 'Hypothalamus', 'Medulla'],
            'What is the longest muscle in the human body?' => ['Sartorius', 'Biceps', 'Quadriceps', 'Gluteus maximus'],
            'Which natural disaster is measured using the Richter scale?' => ['Earthquake', 'Hurricane', 'Flood', 'Tornado'],
            'What is the chemical formula for ozone?' => ['O₃', 'O₂', 'CO₂', 'H₂O'],
            'What country was once known as Ceylon?' => ['Sri Lanka', 'Thailand', 'Myanmar', 'Bangladesh'],
            'Which is the only mammal capable of true flight?' => ['Bat', 'Flying squirrel', 'Glider lemur', 'Colugo'],
            'Which ancient city was buried by the eruption of Mount Vesuvius?' => ['Pompeii', 'Athens', 'Troy', 'Alexandria'],
            'Which scientist is credited with the discovery of electrons?' => ['J.J. Thomson', 'Niels Bohr', 'Ernest Rutherford', 'James Chadwick'],
            'What is the study of insects called?' => ['Entomology', 'Ornithology', 'Herpetology', 'Ichthyology'],
            'What’s the capital of Azerbaijan?' => ['Baku', 'Tbilisi', 'Yerevan', 'Ashgabat'],
            'What’s the name of the legendary island said to have sunk into the ocean?' => ['Atlantis', 'El Dorado', 'Avalon', 'Shangri-La'],
            'What is the name of the fear of heights?' => ['Acrophobia', 'Agoraphobia', 'Claustrophobia', 'Arachnophobia'],
            'What is the term for a solution with a pH less than 7?' => ['Acidic', 'Basic', 'Neutral', 'Alkaline'],
            'Which organ produces insulin?' => ['Pancreas', 'Liver', 'Kidney', 'Stomach'],
            'What is the largest land carnivore?' => ['Polar bear', 'Grizzly bear', 'Siberian tiger', 'Lion'],
            'Who discovered the theory of electromagnetism?' => ['James Clerk Maxwell', 'Michael Faraday', 'Nikola Tesla', 'Thomas Edison'],
            'What is the collective name for the bones of the spine?' => ['Vertebrae', 'Femurs', 'Carpals', 'Tarsals'],
            'Which war lasted from 1950 to 1953?' => ['Korean War', 'Vietnam War', 'World War II', 'Gulf War'],
            'What is the deepest point in Earth’s oceans?' => ['Challenger Deep', 'Tonga Trench', 'Java Trench', 'Puerto Rico Trench'],
            'What is the smallest unit of matter?' => ['Atom', 'Molecule', 'Proton', 'Electron'],
            'Which vitamin is primarily obtained from sunlight?' => ['Vitamin D', 'Vitamin C', 'Vitamin A', 'Vitamin K'],
            'What is the rarest naturally occurring element on Earth?' => ['Astatine', 'Francium', 'Plutonium', 'Promethium'],
            'Which country has the most pyramids in the world?' => ['Sudan', 'Egypt', 'Mexico', 'Peru'],
            'What does “HTTP” stand for?' => ['HyperText Transfer Protocol', 'High Tech Transport Protocol', 'Host Transfer Text Protocol', 'Hyper Tool Transfer Path'],
            'Which blood vessels carry blood away from the heart?' => ['Arteries', 'Veins', 'Capillaries', 'Nerves'],
            'Which famous battle took place in 1066?' => ['Battle of Hastings', 'Battle of Agincourt', 'Battle of Bannockburn', 'Battle of Bosworth'],
            'What is the mathematical term for a quantity with both magnitude and direction?' => ['Vector', 'Scalar', 'Tensor', 'Matrix'],
            'Which composer went deaf later in life yet continued to compose?' => ['Beethoven', 'Mozart', 'Brahms', 'Schubert'],
            'What is the study of human societies and cultures called?' => ['Anthropology', 'Sociology', 'Psychology', 'Philosophy'],
            'What is the most abundant element in the universe?' => ['Hydrogen', 'Helium', 'Oxygen', 'Carbon'],
            'Which layer of Earth lies between the crust and the core?' => ['Mantle', 'Lithosphere', 'Asthenosphere', 'Biosphere'],
            'Which African country was formerly known as Zaire?' => ['Democratic Republic of the Congo', 'Gabon', 'Central African Republic', 'Namibia'],
        ];


        $insertQuestions = function ($questions, $difficultyId) use ($now) {
            foreach ($questions as $questionText => $answerList) {
                $questionId = DB::table('questions')->insertGetId([
                    'text' => $questionText,
                    'difficulty_id' => $difficultyId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $correctAnswer = $answerList[0];
                $shuffledAnswers = collect($answerList)->shuffle();
                foreach ($shuffledAnswers as $answerText) {
                    DB::table('answers')->insert([
                        'question_id' => $questionId,
                        'text' => $answerText,
                        'status' => $answerText === $correctAnswer,
                    ]);
                }
            }
        };

        $insertQuestions($easyQuestions, 1);
        $insertQuestions($moderateQuestions, 2);
        $insertQuestions($hardQuestions, 3);
    }
}
