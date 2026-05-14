<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestoreMissingBlogPostsSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title'              => 'How to Rent in Australia as a New Immigrant',
                'slug'               => 'how-to-rent-in-australia-as-a-new-immigrant',
                'category'           => 'Housing',
                'excerpt'            => 'Renting in Australia with no local credit history, no rental references, and no idea what a tenancy ledger is — here is exactly how to make it work as a brand-new arrival.',
                'author_name'        => 'SettleANZ Team',
                'reading_time'       => '8 min read',
                'image'              => 'media/blog/rent-australia-immigrant.webp',
                'image_class'        => 'object-cover',
                'intro_content'      => 'Finding a rental in Australia as a new immigrant is one of the hardest parts of settling in. Landlords and agents want local rental history, Australian references, and proof of stable income — none of which you have yet. But thousands of immigrants do it every year, and there are proven ways to make your application stand out even without the usual paperwork.',
                'checks_content'     => implode("\n", [
                    '✔ Get your Tax File Number (TFN) sorted before applying — agents want to see it',
                    '✔ Open an Australian bank account and show at least 3 months of rent in savings',
                    '✔ Have 6 weeks of bond + 2 weeks advance rent ready in cash',
                    '✔ Gather overseas rental references and have them translated if not in English',
                    '✔ Write a strong cover letter explaining your situation and employment status',
                    '✔ Offer to pay 2–3 months rent in advance if you can — this is legal and often accepted',
                    '✔ Get a local rental guarantor (employer, sponsor, or community contact) if possible',
                    '✔ Check NSW Fair Trading, VCAT, or your state authority for tenancy rights',
                ]),
                'next_steps_content' => implode("\n", [
                    '→ Start with short-term furnished accommodation for your first 4–6 weeks while you look',
                    '→ Use real estate portals like realestate.com.au and domain.com.au for listings',
                    '→ Attend physical inspections — agents favour applicants who show up in person',
                    '→ Apply to multiple properties at the same time to increase your odds',
                    '→ Consider a relocation consultant if you are moving with family or on a tight timeline',
                ]),
                'body_html'          => '<h2>Why Renting as a New Immigrant is Hard</h2>
<p>Australian rental markets — especially Sydney and Melbourne — are among the most competitive in the world. Vacancy rates in major cities regularly sit below 2%, which means landlords and property managers have many applications to choose from. As a new immigrant, your application starts at a disadvantage: no Australian rental history, no local employer reference yet, and no credit history in the country.</p>
<p>This does not mean it is impossible. It means you need to compensate with other proof of reliability — and know exactly what to include.</p>

<h2>What Goes Into an Australian Rental Application</h2>
<p>Most agents use an online portal like 1Form or Ignite. You will be asked to provide:</p>
<ul>
<li>100 points of ID (passport + visa grant notice usually clears this)</li>
<li>Proof of income — employment contract, offer letter, or bank statements</li>
<li>Rental history — overseas references work; get them in writing on letterhead</li>
<li>Personal references — employer, doctor, community leader, or religious leader</li>
<li>Next of kin and emergency contact details</li>
</ul>

<h2>How to Make Your Application Competitive</h2>
<p>The single most effective thing you can do is <strong>write a cover letter</strong>. Explain who you are, why you are in Australia, what visa you are on, and how long you plan to stay. Agents appreciate honesty and context. A well-written letter humanises your application in a pile of faceless forms.</p>
<p>Second, <strong>offer advance rent</strong>. Paying 1–3 months upfront shows financial commitment and reduces the agent\'s perceived risk. This is perfectly legal in all Australian states, though some states cap it — check your state rules.</p>
<p>Third, <strong>be flexible on the start date</strong>. If the property has been vacant for a while, the landlord wants it filled. Offering to start immediately or matching their preferred date helps.</p>

<h2>Understanding the Rental Bond</h2>
<p>In Australia, a rental bond is a security deposit — typically 4 weeks rent — paid before you move in. It is lodged with a government body (not kept by the landlord) and returned at the end of your tenancy if there is no damage or unpaid rent. Budget for bond plus the first 2 weeks of rent in advance, which means you need roughly 6 weeks of rent in liquid cash before you move in.</p>

<h2>Short-Term Accommodation While You Search</h2>
<p>Most immigrants spend 4–8 weeks in short-term accommodation — a serviced apartment, an Airbnb, or a furnished share house — while they do their rental search. This is normal and expected. It also gives you time to learn the suburb, commute routes, and local amenities before you commit to a 12-month lease.</p>

<h2>State-by-State Differences</h2>
<p>Tenancy law is governed at state level in Australia. The key bodies are:</p>
<ul>
<li><strong>NSW:</strong> NSW Fair Trading — residential tenancy bond lodged with the Rental Bond Board</li>
<li><strong>VIC:</strong> Consumer Affairs Victoria — bond lodged with the Residential Tenancies Bond Authority</li>
<li><strong>QLD:</strong> Residential Tenancies Authority (RTA)</li>
<li><strong>WA:</strong> Department of Mines, Industry Regulation and Safety</li>
<li><strong>SA:</strong> Consumer and Business Services</li>
</ul>
<p>Each state has slightly different rules on notice periods, rent increases, and repairs. Familiarise yourself with your state\'s rights before signing.</p>',
                'is_published'       => 1,
                'is_featured_home'   => 0,
                'published_at'       => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'title'              => 'How to Read an Australian Rental Listing',
                'slug'               => 'how-to-read-an-australian-rental-listing',
                'category'           => 'Housing',
                'excerpt'            => 'Australian rental listings are full of abbreviations and local jargon that confuse new arrivals. Here is a plain-English guide to decoding every part of a listing before you apply.',
                'author_name'        => 'SettleANZ Team',
                'reading_time'       => '6 min read',
                'image'              => 'media/blog/rental-listing-australia.webp',
                'image_class'        => 'object-cover',
                'intro_content'      => 'When you first look at Australian rental listings on realestate.com.au or domain.com.au, they can feel like they are written in a different language. "2BR/1BA + OSP", "strata title", "EBR", "pets on approval" — if you did not grow up here, none of this is obvious. This guide explains every common term and abbreviation so you can read a listing confidently and know what questions to ask before you inspect.',
                'checks_content'     => implode("\n", [
                    '✔ Check whether the weekly rent price is stated as per week (pw) not per month',
                    '✔ Confirm "available from" date — some listings are advertised weeks in advance',
                    '✔ Note bond amount — usually 4 weeks rent, paid before you move in',
                    '✔ Check whether water usage is included or billed separately',
                    '✔ Look for strata by-laws if it is an apartment — pets and renovations may be restricted',
                    '✔ Clarify "pets on approval" — this means ask, not automatic yes',
                    '✔ Check council zone and flood/bushfire overlays on council websites for houses',
                    '✔ Confirm parking type — OSP (off-street parking) vs. street parking matters a lot',
                ]),
                'next_steps_content' => implode("\n", [
                    '→ Use the Domain suburb profile and realestate.com.au suburb insights to benchmark rent',
                    '→ Register for inspection times as soon as a listing goes live — spots fill fast',
                    '→ Bring your 100-point ID checklist to the inspection so you can apply on the spot',
                    '→ Use the property address to check Google Street View and nearby transport before attending',
                    '→ Ask the agent directly about council rates, strata levies, and who pays for what',
                ]),
                'body_html'          => '<h2>The Price</h2>
<p>Australian rental prices are almost always quoted as a <strong>weekly rate</strong>, not monthly. If a listing says "$650 pw", that means $650 per week. To estimate your monthly cost, multiply by 52 and divide by 12 — so $650 pw works out to roughly $2,817 per month. This trips up many new arrivals who are used to monthly rent figures.</p>
<p>Some listings will also show a bond amount. This is typically 4 weeks of rent, though it varies by state.</p>

<h2>Common Abbreviations Decoded</h2>
<ul>
<li><strong>BR / Bed</strong> — Bedroom. "2BR" means two bedrooms.</li>
<li><strong>BA / Bath</strong> — Bathroom. "1BA" means one bathroom.</li>
<li><strong>OSP</strong> — Off-street parking. Could be a garage, carport, or driveway bay.</li>
<li><strong>LUG</strong> — Lock-up garage.</li>
<li><strong>EBR</strong> — Ensuite bathroom (a bathroom attached directly to the main bedroom).</li>
<li><strong>OFP</strong> — Open fireplace.</li>
<li><strong>S/C</strong> — Split-cycle air conditioning (heats and cools).</li>
<li><strong>DW</strong> — Dishwasher.</li>
<li><strong>BIR</strong> — Built-in robes (wardrobes built into the wall).</li>
<li><strong>WIR</strong> — Walk-in robe.</li>
<li><strong>Alfresco</strong> — A covered outdoor entertaining area, usually paved.</li>
<li><strong>Strata</strong> — An apartment or townhouse that is part of a strata scheme, meaning owners pay shared body corporate fees. As a renter this matters because strata by-laws govern what you can and cannot do.</li>
<li><strong>pw</strong> — Per week.</li>
<li><strong>pcm</strong> — Per calendar month (less common in Australia but occasionally used).</li>
</ul>

<h2>What "Inspection" Means</h2>
<p>Australian listings typically advertise scheduled open-for-inspection (OFI) times — usually a 15–30 minute window on a Saturday morning. You turn up, walk through the property, and if you want to apply, you collect or scan a QR code for the application form. There is no obligation to book in some states, but registering via the agent portal means you get notified if the time changes.</p>
<p>Private inspections are also possible — contact the agent directly to arrange a time outside the open home window. This is especially useful for competitive properties where you want to build rapport with the agent.</p>

<h2>Understanding the Listing Description</h2>
<p>Listing descriptions are written by the agent and are deliberately positive. Learn to read between the lines:</p>
<ul>
<li><strong>"Cosy"</strong> — small</li>
<li><strong>"Great bones"</strong> — outdated but structurally sound</li>
<li><strong>"Investor-grade finish"</strong> — basic, functional, nothing luxurious</li>
<li><strong>"Vibrant neighbourhood"</strong> — busy, possibly noisy</li>
<li><strong>"Ready for the right tenant"</strong> — may have had trouble finding someone</li>
<li><strong>"Pets on approval"</strong> — you must ask; approval is not guaranteed and may depend on strata by-laws</li>
</ul>

<h2>What to Check Before You Inspect</h2>
<p>Before you drive to an inspection, do these three things:</p>
<ol>
<li><strong>Check Google Street View</strong> — see the street, surrounding buildings, nearby businesses.</li>
<li><strong>Check public transport</strong> — use the Google Maps transit layer to see what bus/train routes service the area and how long commutes will take.</li>
<li><strong>Check flood and bushfire risk</strong> — many Australian councils publish overlay maps online. Important for houses, especially in Queensland, Western Australia, and outer Melbourne.</li>
</ol>

<h2>Rent Negotiation</h2>
<p>Rent is negotiable in Australia, especially if a property has been listed for more than 3–4 weeks. Agents will not advertise this, but a polite offer of $10–20 pw below the listed price — backed by a strong application — is accepted more often than you might expect. In a tight market (vacancy under 2%) negotiation is harder; in a softer market it is common.</p>',
                'is_published'       => 1,
                'is_featured_home'   => 0,
                'published_at'       => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ];

        foreach ($posts as $post) {
            if (!DB::table('blog_posts')->where('slug', $post['slug'])->exists()) {
                DB::table('blog_posts')->insert($post);
                echo "Inserted: {$post['title']}\n";
            } else {
                echo "Already exists: {$post['title']}\n";
            }
        }
    }
}
