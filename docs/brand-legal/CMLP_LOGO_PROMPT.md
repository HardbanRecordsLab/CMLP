# CMLP — Prompt do generowania logo

## Kontekst marki (do przypomnienia przy każdym użyciu promptu)

- **Nazwa kreatywna/katalogowa (widoczna publicznie):** Collective Music Licensing Project
- **Nazwa formalna/biznesowa (umowy, faktury, B2B):** Creative Music Licensing Partners
- **Skrót używany wszędzie na wierzchu:** CMLP
- **Model marki:** anonimowy kolektyw twórców/producentów — na zewnątrz widoczna jest wyłącznie marka CMLP, tożsamość konkretnych twórców pozostaje ukryta
- **Odbiorca:** klienci biznesowi (B2B) kupujący licencje muzyczne — nie fani, nie słuchacze indywidualni
- **Ton marki:** profesjonalny, godny zaufania, "czysty prawnie" katalog — nie festiwalowy, nie "band merch"

## Główny prompt (do Midjourney / DALL·E / ChatGPT Images / Ideogram)

```
Minimalist, professional logo design for "CMLP" — a B2B music licensing
catalog brand. Modern geometric monogram combining the letters C M L P
into a single clean mark, works equally well as a small icon (favicon)
and a large wordmark. Style: corporate trust meets audio/sound identity —
subtle sound-wave or equalizer motif integrated abstractly into the
lettering, not literal or decorative. Flat vector design, no gradients,
no photorealism, no mascots, no human figures, no faces. Color palette:
deep navy or charcoal primary, with a single confident accent color
(teal, amber, or electric blue) — must work in pure black on white and
pure white on black. Balanced negative space, sharp geometric edges,
scalable at both 32px favicon size and large format print. Feels like a
serious licensing/publishing entity (think: a hybrid between a law firm's
restraint and a music-tech startup's modernity) — NOT a band logo, NOT a
festival poster, NOT an artist emblem.
```

## Wariant A — sam monogram / ikona (favicon, avatar)

```
Same brand system as above, icon-only version: a compact abstract
monogram built from C, M, L, P negative-space or overlapping strokes,
legible at 16x16px, single or two-color, square or circular container
option, no wordmark text included.
```

## Wariant B — pełny wordmark (nagłówek strony, dokumenty)

```
Same brand system as above, full horizontal wordmark: "CMLP" in a custom
geometric sans-serif, tracked letterspacing, paired with a small
matching icon mark to the left. Must remain legible in a single color
(for use on official documents/letterhead) as well as in the full brand
color palette.
```

## Wariant C — wersja pod dokumenty prawne / umowy (jednokolorowa)

```
Same CMLP monogram + wordmark, simplified to pure black line/fill only,
no color, no shading — suitable for a Word/PDF document letterhead and
for stamping/watermarking contracts.
```

## Czego unikać (negative prompt / uwagi)

- żadnych twarzy, sylwetek ludzi, mikrofonów, gitar, płyt winylowych (zbyt dosłowne / "band")
- żadnych gradientów neonowych, "AI-glow" efektów — ma wyglądać jak dojrzała marka, nie jak wygenerowany hype
- unikać skojarzeń z konkretnym gatunkiem muzycznym (ma pasować do całego katalogu — EDM, ambient, corporate, cinematic itd.)
- nazwa "Collective Music Licensing Project" / "Creative Music Licensing Partners" nie musi pojawiać się w logo w pełnym brzmieniu — logo nosi wyłącznie skrót **CMLP**, pełne nazwy żyją w stopce, dokumentach i métadanych

## Docelowe pliki do wygenerowania

1. Logo pełnokolorowe (wordmark + ikona) — PNG z przezroczystym tłem, min. 2000px szerokości
2. Wersja monochromatyczna czarna — do dokumentów/umów
3. Wersja monochromatyczna biała — do ciemnych teł
4. Sam monogram/ikona — kwadrat 512x512px (favicon/avatar)
5. Wersja pozioma (wordmark + ikona obok) — do nagłówka strony WordPress
