=== Fram ===
Requires at least: 7.1
Tested up to: 7.1
Requires PHP: 8.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hybrid starter theme for a car workshop site: templates, header, footer, base
styles, forms. Content patterns are added per project. The version with
ready-made sections and starter pages is the "Fram Full" theme (fram-full).

== Description ==

* Classic PHP templates (header.php, footer.php, page.php, ...).
* Header and footer are block template parts (parts/*.html). Edit them in
  Appearance → Design → Patterns → Template Parts. Saved versions live in the
  database and override the theme files.
* Page content is built with core blocks and the theme patterns.
* theme.json holds the design tokens (colors, fonts, font sizes, spacing,
  icons) and the base styles of elements and blocks.
* style.css holds the component styles: the block styles registered in
  inc/block-styles.php, forms and the header behaviour. It is loaded on the
  front end and in the editor canvas.

== Structure ==

* patterns/header.php, patterns/footer.php – content of the header and footer
  template parts (parts/*.html load them).
* patterns/hidden-404.php – content of the 404 page.
* inc/setup.php – supports, assets, pattern categories "fram" and
  "fram-pages", logo placeholder, template part helpers.
* inc/block-styles.php – block styles for the editor's "Styles" panel.
* inc/forms.php, forms/*.txt – Contact Form 7 forms.
* inc/eu-kontroll.php, assets/js/eu-kontroll.js – EU-kontroll lookup.
* assets/js/theme.js – sticky header state, back-to-top button, reveal.

== Getting started ==

1. Activate Contact Form 7, then open the dashboard once: the theme creates the
   "Bestill time" (slug bestill-time) and "Kontakt" (slug kontakt) forms.
2. Upload the logo in Appearance → Design → Patterns → Template Parts → Header
   (Site Logo block). A placeholder is shown until then.
3. Replace the placeholder texts, phone numbers, e-mail addresses and partner
   badges in the header and footer.

== Adding a pattern ==

1. Add a file to patterns/ with a header like:

   Title: Hero
   Slug: fram/hero
   Categories: fram
   Description: ...

   Full page patterns use "Categories: fram-pages", "Block Types:
   core/post-content" and "Post Types: page" so they show up in the
   "Choose a pattern" dialog.
2. If the pattern needs its own block style, register it in
   inc/block-styles.php and put its CSS in style.css (one section per
   component, breakpoints 1024px / 599px).
3. Assets go to assets/images and assets/icons.

The fram-full theme has finished examples: hero with CTA panel, service tiles,
about box, cards, forms and contact sections.

== Block styles ==

Group: Header-linje, Seksjon navy/dis/lys, Skjemapanel, Kort.
Column: Seksjon navy/dis/lys, Kort. Columns: Seksjon navy/dis/lys,
Fire kolonner. Heading: Understreket tittel, Display. Paragraph: Display,
Ikon (e-post, telefon, klokke, Facebook, info, kart). Post/Query title:
Understreket tittel. Button: Til toppen (and the core Outline style).

Add the CSS class "reveal" (Advanced → Additional CSS class) to any block to
fade it in on scroll.

Breakpoints: tablet ≤ 1024px, mobile ≤ 599px (same as settings.viewport in
theme.json). Up to 1024px the header is one row: logo on the left, contact
icons (clock, Facebook, e-mail, phone; the clock is hidden below 600px) and the
menu button on the right.

== Icons ==

Font Awesome Free 7.3.1 is self-hosted in assets/vendor/fontawesome and loaded
on the front end and in the editor. The "Ikon: …" paragraph styles, the
back-to-top button and the menu button use its glyphs.

* In a Custom HTML block: <i class="fa-solid fa-phone" aria-hidden="true"></i>
* In style.css: content: "\f095"; font: var(--fa-font-solid);
  (also --fa-font-regular, --fa-font-brands)

To update, replace css/all.min.css and webfonts/ with the files from the
@fortawesome/fontawesome-free package and change the version in
inc/setup.php.

== Forms ==

Shortcode: [fram_form slug="bestill-time"] or [fram_form slug="kontakt"],
placed in a Group with the "Skjemapanel" style.

== EU-kontroll lookup ==

Shortcode: [fram_eu_kontroll]. REST: GET /wp-json/fram/v1/eu-kontroll?regnr=AB12345

No API is connected. Until a provider is hooked into the
"fram_eu_kontroll_lookup" filter, the form opens the Statens vegvesen lookup
page. A provider returns array( 'deadline' => 'YYYY-MM-DD' ) or a WP_Error.

== Credits ==

* PT Sans – ParaType, SIL Open Font License 1.1 (assets/fonts/pt-sans/OFL.txt)
* Montserrat – The Montserrat Project Authors, SIL Open Font License 1.1 (assets/fonts/montserrat/OFL.txt)
* Font Awesome Free – Fonticons, Inc. Icons CC BY 4.0, fonts SIL OFL 1.1, code MIT (assets/vendor/fontawesome/LICENSE.txt)
* Placeholder images – made for this theme, GPLv2 or later.
