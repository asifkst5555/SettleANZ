<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class ReadRentalListingPostSeeder extends Seeder
{
    public function run(): void
    {
        $bodyHtml = <<<'HTML'
<p><strong>A Decoded Guide for New Immigrants &mdash; Listing Language, Red Flags, and What to Check at Every Inspection.</strong></p>

<blockquote>
    <p>"The listing said 'compact studio with character'. Character turned out to mean I could touch both walls when I stretched my arms &mdash; and I would still be freezing at night."</p>
    <cite>— From a client's first month in Melbourne</cite>
</blockquote>

<p>By the time most of my clients attend their first property inspection in Australia, they have already spent hours on Domain and realestate.com.au, bookmarking listings and comparing photos. What they have not done is learn to read between the lines.</p>

<p>Australian rental listings are written by property managers whose job is to fill the property, not to warn you about the draughty windows, the bathroom with no ventilation, or the fact that "quiet street" means the bin trucks come at 5 am on Wednesdays.</p>

<p>This is not exactly dishonesty; it is the standard marketing language. Once you understand how to decode it, you will read every listing quite differently.</p>

<p>As someone who has been navigating the Australian rental market for over two decades &mdash; first as an immigrant myself, and now as an advisor to new arrivals &mdash; I'm gifting you this inspection toolkit, so that you don't get any surprises after moving in to your leased home.</p>

<h2 id="terms-decoded">Australian Rental Listing Terms Decoded</h2>

<p>The following terms appear in Australian rental listings constantly. Here is what they actually mean, and what to look for when you walk through the door.</p>

<h3>"Cosy"</h3>
<ul>
    <li><strong>What it means:</strong> Small. Often with limited storage and no space for a desk or guests.</li>
    <li><strong>What to check at inspection:</strong> Can a bed and a desk fit comfortably? Will you feel confined after a month of working or studying from home?</li>
</ul>

<h3>"Sun-drenched" or "light-filled"</h3>
<ul>
    <li><strong>What it means:</strong> Significant natural light, which can be pleasant, but in an Australian summer can mean the apartment becomes genuinely difficult to cool.</li>
    <li><strong>What to check at inspection:</strong> Which direction do the main windows face? Is there adequate shade, blinds, or curtains? In cities like Sydney and Brisbane, this can determine whether the property is comfortable for three months of the year.</li>
</ul>

<h3>"Original features"</h3>
<ul>
    <li><strong>What it means:</strong> The property has not been renovated. Sometimes genuinely charming; more often it means old wiring, minimal insulation, and windows that do not seal properly in winter.</li>
    <li><strong>What to check at inspection:</strong> Single-pane windows, gaps around door frames, aged power points, and signs of condensation or damp. These directly affect your comfort and your electricity bill.</li>
</ul>

<h3>"As-is"</h3>
<ul>
    <li><strong>What it means:</strong> The landlord is not planning to repair anything before you move in. What you see is what you are signing for.</li>
    <li><strong>What to check at inspection:</strong> Any visible damage, deteriorating appliances, peeling paint, or mould. Assume these will remain unless you obtain a written commitment to address them before signing the lease.</li>
</ul>

<h3>"Walk to transport"</h3>
<ul>
    <li><strong>What it means:</strong> Relative to whoever wrote the listing. In practice, this can mean six minutes or twenty-two minutes, depending on how optimistic the agent is feeling.</li>
    <li><strong>What to check at inspection:</strong> Always open Google Maps and verify the actual walking time to the nearest station, tram stop, or bus stop in both directions.</li>
</ul>

<h3>"Low-maintenance"</h3>
<ul>
    <li><strong>What it means:</strong> A small courtyard or no garden at all. Sometimes code for a concrete-only outdoor space with no greenery.</li>
    <li><strong>What to check at inspection:</strong> Is there any outdoor space you would actually want to use, or is it primarily a bin enclosure?</li>
</ul>

<h3>"Quiet street"</h3>
<ul>
    <li><strong>What it means:</strong> Relative. A street can be genuinely quiet, or it can be quiet between 10pm and 4am and loud the rest of the time.</li>
    <li><strong>What to check at inspection:</strong> Visit at a different time of day if possible. Check proximity to main roads, schools, and commercial areas using Google Maps.</li>
</ul>

<h3>"Freshly painted" or "recently updated"</h3>
<ul>
    <li><strong>What it means:</strong> There has been some surface-level work done, but this does not indicate a full renovation or that underlying issues have been addressed.</li>
    <li><strong>What to check at inspection:</strong> Look behind the fresh paint. Check window seals, under sinks for water damage, and behind doors for mould that may have been painted over.</li>
</ul>

<h2 id="inspection-checklist">What to Look for at an Australian Rental Inspection</h2>

<p>Open inspections in competitive rental markets move quickly. You may have ten to fifteen minutes inside the property, surrounded by other prospective tenants. It is easy to spend that time admiring the light and forget to turn on the kitchen tap.</p>

<p>The following is a systematic checklist. Work through it at every inspection, regardless of how promising the property looks online.</p>

<blockquote>
    <p>"I fell in love with the place in the first two minutes. The light was beautiful. I signed within 48 hours. By month two, I realised the hot water system was failing, the bathroom had mould behind the tiles, and the oven had never worked properly. None of it was in the listing."</p>
    <cite>— From a client's first Australian lease</cite>
</blockquote>

<h3>The Inspection Checklist: What to Test and Photograph</h3>

<h4>Utilities and appliances</h4>
<ul>
    <li>Turn on every tap &mdash; kitchen, bathroom, laundry. Check water pressure and how long hot water takes to arrive.</li>
    <li>Test the stovetop and oven if accessible. If elements or burners do not work, document it before signing.</li>
    <li>Flush the toilet. Check that it refills properly.</li>
    <li>Test every light switch in every room.</li>
    <li>Check the hot water system &mdash; ask the agent how old it is if it is not visible.</li>
</ul>

<h4>Windows, doors, and seals</h4>
<ul>
    <li>Open every window. Confirm it closes and locks properly again.</li>
    <li>Check window seals and frames for gaps, rot, or single-pane glass. These affect both comfort and heating costs significantly.</li>
    <li>Test every external door. It should close flush without force.</li>
</ul>

<h4>Moisture, mould, and water damage</h4>
<ul>
    <li>Examine every ceiling corner in every room for water staining &mdash; this indicates a leak, past or present.</li>
    <li>Check under the bathroom and kitchen sinks for water damage, swelling, or mould.</li>
    <li>Open built-in robes and wardrobes. Mould in enclosed spaces is a sign of a broader moisture problem.</li>
    <li>Smell the air when you first enter. A musty odour that persists is a warning sign, even if nothing is immediately visible.</li>
</ul>

<h4>Heating and cooling</h4>
<ul>
    <li>Ask what heating is provided in each bedroom specifically &mdash; not just the living area.</li>
    <li>Locate any air conditioning units and confirm they are operational. Check how old they are if possible.</li>
    <li>If the property has no built-in heating, factor the cost of portable electric heaters into your assessment, particularly for Melbourne, Canberra, and regional areas.</li>
</ul>

<h4>Storage, phone signal, and internet</h4>
<ul>
    <li>Check actual storage: cupboards, linen press, garage or shed if applicable. Photos often make storage look larger than it is.</li>
    <li>Check your phone signal inside the property. Thick walls or basement-level units can have surprisingly poor reception.</li>
    <li>Ask the agent what internet infrastructure is available &mdash; NBN, fibre to the premises, or fixed wireless. This matters significantly if you work remotely.</li>
</ul>

<h2 id="dealbreakers">What Is Normal vs What Is a Dealbreaker in Australian Rentals</h2>

<p>Standards in the Australian rental market may differ from what you are accustomed to at home. Knowing where the line is between acceptable and unacceptable protects you before you sign your first lease agreement.</p>

<h3>Mould</h3>
<p>Some surface mould in a bathroom around grout or window sills is common in older Australian rentals, particularly in Melbourne, Sydney, and coastal cities with high humidity. A small amount in a poorly ventilated bathroom is not ideal, but it is manageable if the landlord provides a written commitment to address it before you move in.</p>

<p>These, however, are dealbreakers:</p>
<ul>
    <li>Black mould on walls or ceilings in living areas or bedrooms</li>
    <li>A mould or musty smell when you walk through the front door</li>
    <li>Condensation on the inside of windows throughout the property &mdash; this indicates a systemic moisture problem</li>
</ul>

<p>Under Australian tenancy law, a rental property must be safe, secure, and in a reasonable state of repair. A property with serious mould does not meet that standard. You have legal grounds to walk away or require full remediation before signing. If you observe mould at an inspection, photograph it immediately and note the room and location.</p>

<h3>Heating and cooling</h3>
<p>Many people relocating to Australia are surprised to find that heating is not standard in all rental properties. Melbourne winters regularly drop to 5&ndash;8&deg;C overnight. Canberra regularly falls below zero. Some older properties &mdash; particularly Victorian-era terraces and apartments &mdash; have no built-in heating at all, or a single wall unit in the living area only.</p>

<p>At every inspection, ask specifically what heating is provided in each bedroom. If the answer is "portable heaters are available" or the question is met with silence, factor that into your decision. Running portable electric heaters through a Melbourne winter adds meaningfully to your power bills.</p>

<p>Cooling matters equally. In Sydney and Brisbane, a property with no air conditioning and poor cross-ventilation will be genuinely uncomfortable for two to three months of the year.</p>

<h3>General property condition</h3>
<p>An older property is not automatically a poor choice. Many well-maintained older rentals offer more space and better value than newer builds. The distinction is between a property that has aged well and one that has been neglected.</p>

<p>Legitimate concerns to raise with the agent before signing include: non-functioning appliances, pest evidence, broken fixtures, and any damage not noted in the property condition report. Raise these in writing, and confirm in writing that they will be addressed before your move-in date or noted as pre-existing in the condition report.</p>

<div class="article-callout">
    <h3>Pro tip</h3>
    <p>If something feels wrong at an inspection, trust that instinct. A listing that looks perfect online but feels off in person almost always feels off for a reason. The property manager's job is to lease the property. Your job is to protect the next twelve months of your life.</p>
</div>

<h2 id="tenant-rights">Know Your Rights as a Tenant in Australia Before You Sign</h2>

<p>Australian tenancy law provides meaningful protections for renters, and understanding the basics before you sign puts you in a significantly stronger position. Specific provisions vary by state and territory, but the following principles apply broadly:</p>

<ul>
    <li>A rental property must be provided in a clean condition and in a reasonable state of repair at the start of your tenancy.</li>
    <li>The landlord is responsible for maintaining the property and carrying out repairs within a reasonable timeframe. Urgent repairs such as a failed hot water system or a gas leak must be addressed promptly.</li>
    <li>You have the right to a condition report at the start of your tenancy. Complete it carefully and photograph anything not accurately described. This document protects you when you vacate.</li>
    <li>Bond (usually four weeks' rent) must be held in a government-managed trust account &mdash; not by the landlord or agent directly. Ensure you receive a bond lodgement receipt.</li>
    <li>If a landlord wants to increase your rent, they must provide the legally required notice period, which varies by state.</li>
</ul>

<p>State and territory tenancy authorities publish plain-language guides covering your rights in detail. Consumer Affairs Victoria, NSW Fair Trading, and equivalent bodies in each state are the authoritative sources and are free to contact.</p>

<h2 id="conclusion">Conclusion: Read the Listing, Then Trust What You See in Person</h2>

<p>The ability to read a rental listing accurately and to conduct a thorough inspection &mdash; despite the pressure of a competitive market &mdash; is one of the most practical skills a new immigrant to Australia can develop.</p>

<p>It takes time and a few inspections to build confidence, but once you have it, you will not be misled by marketing language or rushed into a decision that does not serve you.</p>

<p>A quick summary of what to carry into every inspection:</p>

<ul>
    <li><strong>Decode the listing before you attend.</strong> Translate marketing language into what it actually means for liveability, cost, and comfort.</li>
    <li><strong>Work through your checklist methodically.</strong> Taps, windows, appliances, moisture, heating, signal, storage. Do not let the atmosphere of a busy open inspection rush you past the fundamentals.</li>
    <li><strong>Know what is a dealbreaker.</strong> Serious mould, absent heating in cold climates, and non-functioning appliances are not minor inconveniences. These are legitimate grounds to walk away or negotiate in writing before signing.</li>
    <li><strong>Understand your rights before you sign.</strong> The condition report, bond lodgement, and repair obligations are not formalities, these are legal protections. Use them.</li>
</ul>

<p>The rental market in Australia is competitive, but it is also navigable &mdash; particularly once you stop reading listings at face value and start reading them as the professional documents they are.</p>

<p>If you would rather not make this journey alone, I work with new immigrants through every stage of the rental process &mdash; from documentation and short-term accommodation through to lease signing. Your first Australian home should be chosen, not simply accepted.</p>

<div class="article-cta-card">
    <h3>Need help with your rental search?</h3>
    <p>Book a consultation to discuss your rental search situation. We will walk through listings, suburbs, and inspection priorities together.</p>
    <button class="button button--small" type="button" data-open-lead-modal>Book a consultation</button>
</div>
HTML;

        BlogPost::query()->updateOrCreate(
            ['slug' => 'how-to-read-an-australian-rental-listing'],
            [
                'title' => 'How to Read an Australian Rental Listing',
                'category' => 'Housing',
                'excerpt' => 'A decoded guide for new immigrants — listing language, red flags, and what to check at every inspection in Australia.',
                'author_name' => 'SettleANZ Team',
                'reading_time' => '13 min read',
                'image' => null,
                'image_class' => 'blog-card__image--housing',
                'intro_content' => null,
                'checks_content' => null,
                'next_steps_content' => null,
                'body_html' => $bodyHtml,
                'is_published' => true,
                'is_featured_home' => false,
                'published_at' => now(),
            ]
        );
    }
}
