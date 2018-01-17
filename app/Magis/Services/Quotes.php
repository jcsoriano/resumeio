<?php

namespace App\Magis\Services;

use Carbon\Carbon;
use App\Magis\Activity;
use Illuminate\Support\Facades\Auth;

class Quotes
{
    private $otherQuotes = [
        "Being the richest man in the cemetery doesn't matter to me. Going to bed at night saying we've done something wonderful, that's what matters to me. - Steve Jobs",
        'Innovation distinguishes between a leader and a follower. - Steve Jobs',
        'Sometimes when you innovate, you make mistakes. It is best to admit them quickly, and get on with improving your other innovations. - Steve Jobs',
        "Be a yardstick of quality. Some people aren't used to an environment where excellence is expected. - Steve Jobs",
        "It's really hard to design products by focus groups. A lot of times, people don't know what they want until you show it to them. - Steve Jobs",
        'Design is not just what it looks like and feels like. Design is how it works. - Steve Jobs',
        'I want to put a ding in the universe. - Steve Jobs',
        "My favorite things in life don't cost any money. It's really clear that the most precious resource we all have is time. - Steve Jobs",
        "Sometimes life is going to hit you in the head with a brick. Don't lose faith. - Steve Jobs",
        "I'm convinced that about half of what separates the successful entrepreneurs from the non-successful ones is pure perseverance. - Steve Jobs",
        'Imagination is more important than knowledge. - Albert Einstein',
        'The important thing is not to stop questioning. Curiosity has its own reason for existing. - Albert Einstein',
        'Anyone who has never made a mistake has never tried anything new. - Albert Einstein',
        'Try not to become a man of success, but rather try to become a man of value. - Albert Einstein',
        'No problem can be solved from the same level of consciousness that created it. - Albert Einstein',
        'Everything should be made as simple as possible, but not simpler. - Albert Einstein',
        'I have no special talents. I am only passionately curious. - Albert Einstein',
        "I think life on Earth must be about more than just solving problems... It's got to be something inspiring, even if it is vicarious. - Elon Musk",
        'Great companies are built on great products. - Elon Musk',
        "When Henry Ford made cheap, reliable cars people said, 'Nah, what's wrong with a horse?' That was a huge bet he made, and it worked. - Elon Musk",
        "I think that's the single best piece of advice: constantly think about how you could be doing things better and questioning yourself. - Elon Musk",
        'Go forth and set the world on fire. - Ignatius of Loyola',
        'Act as if everything depended on you; trust as if everything depended on God. - Ignatius of Loyola',
        'Laugh and grow strong - Ignatius of Loyola',
        'Teach us to give and not to count the cost. - Ignatius of Loyola',
        'If our church is not marked by caring for the poor, the oppressed, the hungry, we are guilty of heresy. - Ignatius of Loyola',
        'People work better when they know what the goal is and why. It is important that people look forward to coming to work in the morning and enjoy working. - Elon Musk',
        "If you hire people just because they can do a job, they'll work for your money. But if you hire people who believe what you believe, they'll work for you with blood, sweat, and tears. - Simon Sinek",
        'If you have the opportunity to do amazing things in your life, I strongly encourage you to invite someone to join you. - Simon Sinek',
        "Champions are not the ones who always win races - champions are the ones who get out there and try. And try harder the next time. And even harder the next time. 'Champion' is a state of mind. They are devoted. They compete to best themselves as much if not more than they compete to best others. Champions are not just athletes. - Simon Sinek",
        "It's important to slow down, every now and then, for no other reason than to call someone to say 'Hi.' It doesn't have to be a long conversation. Just calling out of the blue does more to let someone know you still care about them than nearly anything else. - Simon Sinek",
        'The quality of a leader cannot be judged by the answers he gives, but by the questions he asks. - Simon Sinek',
    ];

    public static function loading()
    {
        $quotes = [
            'Have a great day!',
            'Create something great :)',
            'Remember to create something great :)',
            'Ad Astra, Per Aspera!',
            'Carpe Diem!',
            'Do more, Change Lives',
            'Software for a Better World',
            'Work Hard, and Change Things Fast',
            'Do amazing things',
            'Ascende Superius',
            'Pro Deo et Patria',
            'Ora et Labora',
            'Lux in Domino',
            'Ad Majorem Dei Gloriam',
            'Da mihi Animas, caetera tolle',
            'Leave a legacy',
            'Make the world a better place',
        ];
        return $quotes[rand(0, count($quotes) - 1)];
    }
}
