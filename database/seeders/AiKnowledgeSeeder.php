<?php

namespace Database\Seeders;

use App\Models\AiKnowledgeEntry;
use Illuminate\Database\Seeder;

class AiKnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            [
                'title' => 'Why do people migrate to Australia?',
                'content' => 'People migrate to Australia for higher incomes, better quality of life, free healthcare (Medicare), excellent weather, work-life balance, rule of law, education, and career opportunities. Many come to pay off family debts, escape poor weather, or seek a peaceful life. The currency advantage (1 AUD = ~45-70 INR) makes earning in Australia highly valuable for supporting families abroad.',
                'search_keywords' => 'migrate, australia, reasons, income, quality life, healthcare, weather, work-life balance, education, career, currency, family debt',
                'category' => 'migration',
                'priority' => 10,
            ],
            [
                'title' => 'Real migrant experiences in Australia',
                'content' => 'Real migrant stories show both positives and challenges. Positives: free Medicare healthcare, higher salaries (IT contractors earn $70-120/hour), better work-life balance, cleaner air, quality food standards, rule of law, easier business setup, government benefits, solar panel rebates, tenant protection via bond authorities. Challenges: first job may be at a lower level than in home country, need for local experience, occasional racism, tall poppy syndrome (humility expected), English fluency matters for career progression, and introverts may struggle with networking.',
                'search_keywords' => 'migrant experiences, stories, positives, challenges, healthcare, salary, work-life balance, local experience, racism, tall poppy syndrome, english fluency, networking',
                'category' => 'culture',
                'priority' => 10,
            ],
            [
                'title' => 'Challenges new immigrants face in Australia',
                'content' => 'New immigrants face intense homesickness and isolation, missing family deeply. Job hunting is a major hurdle without local experience - employers want local experience but no one gives the first chance. Social isolation is common due to cultural differences and language barriers. Loneliness triggers anxiety and depression. Affordable housing and transport mismatches add stress, with some commuting an hour with multiple train changes. Many struggle to find housing and employment due to lack of local networks.',
                'search_keywords' => 'challenges, immigrants, homesickness, isolation, job hunt, local experience, social isolation, language barriers, anxiety, depression, housing, transport, commute, local networks',
                'category' => 'challenges',
                'priority' => 10,
            ],
            [
                'title' => 'Tips for settling successfully in Australia',
                'content' => 'Key tips: assimilate and explore the country, make effort to talk to people, don\'t only hang around with your own countrymen, work on English language skills and emotional intelligence, be polite and take the high road, understand dignity of labor (all jobs are respected), be humble (tall poppy syndrome is real), be social and form workplace relationships, embrace optimism and constructive criticism, and understand that Australia values meritocracy, low corruption, mutual respect, and environmental protection.',
                'search_keywords' => 'tips, settling, australia, assimilate, explore, english language, emotional intelligence, polite, humility, tall poppy syndrome, social relationships, meritocracy, respect',
                'category' => 'tips',
                'priority' => 10,
            ],
            [
                'title' => 'Pros and cons of living in Australia compared to India',
                'content' => 'Pros: free healthcare for PR/citizens, nicer weather (less humid), freedom of speech/religion/press, higher incomes for same roles, better work-life balance, much less pollution, quality food/grocery standards, high-quality meat, ease of doing business, government benefits, negative gearing for property, lower stamp duty for first home buyers, roadworthy certificate system for used cars, tenant-landlord tribunal system, proper town planning with parks and greenery. Cons: tough to get first job at same seniority level, low tolerance for haughtiness/entitlement, occasional racism, lower career progression for introverts or those with weaker English.',
                'search_keywords' => 'pros, cons, australia vs india, healthcare, weather, freedom speech, income, work-life balance, pollution, food standards, business, property, tenant rights, town planning, job seniority, racism, career progression',
                'category' => 'culture',
                'priority' => 8,
            ],
            [
                'title' => 'Cost of living and salary expectations in Australia',
                'content' => 'Australia has high living costs, especially in Sydney and Melbourne for housing. Median wages are high compared to many OECD countries. Minimum wage is around $20.33/hour. IT contractors earn $70-120/hour. Tradespeople (plumbers, electricians) earn $200-500 for a few hours. After-tax income is roughly 60% of gross (40% tax on high incomes). Superannuation (retirement contribution) adds ~10-12% to total compensation. Regional areas are considerably cheaper than major cities. You need a decent-paying job to enjoy Australia comfortably.',
                'search_keywords' => 'cost living, salary, expectation, minimum wage, it contractor, tradesperson, tax, superannuation, regional, cheaper, sydney, melbourne, expensive, decent job',
                'category' => 'work',
                'priority' => 8,
            ],
            [
                'title' => 'Australian culture and lifestyle',
                'content' => 'Australia is multicultural with no rigid class structure. Australians value mateship, fairness, independence, and work-life balance. The laid-back lifestyle includes parties, get-togethers, and family picnics. Sports are a national fixation (cricket, soccer, rugby, tennis, netball). Coffee culture is strong with excellent cafes everywhere. Food is diverse with cuisines from around the world. Over 500 national parks protect natural heritage. 36,000+ km coastline with abundant beaches. Locals are friendly and expect basic politeness and greetings.',
                'search_keywords' => 'australian culture, lifestyle, multicultural, mateship, fairness, work-life balance, sports, coffee culture, food, diverse, national parks, beaches, friendly, polite, greetings',
                'category' => 'culture',
                'priority' => 8,
            ],
        ];

        foreach ($entries as $entry) {
            AiKnowledgeEntry::create(array_merge($entry, ['is_active' => true]));
        }
    }
}
