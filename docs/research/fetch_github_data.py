"""  """import urllib.request
import json
import sys

def fetch_github_search(query, per_page=15):
    url = f"https://api.github.com/search/repositories?q={query}&sort=stars&order=desc&per_page={per_page}"
    req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
    try:
        with urllib.request.urlopen(req, timeout=15) as response:
            data = json.loads(response.read().decode())
            return data.get("items", [])
    except Exception as e:
        print(f"Error fetching {query}: {e}", file=sys.stderr)
        return []

queries = [
    "ui+component+library+tailwind",
    "ui+component+library+react",
    "ui+component+library+vue",
    "ui+component+library+vanilla+javascript",
    "ui+component+library+bootstrap",
    "ui+component+library+css",
    "ui+kit+free+open+source",
    "component+library+design+system",
    "tailwind+components+free",
    "headless+ui+component+library",
    "html+css+component+library",
    "web+component+library+open+source",
    "dashboard+template+free+open+source",
    "admin+template+free+open+source",
    "landing+page+components+free",
]

all_results = {}
for q in queries:
    items = fetch_github_search(q)
    all_results[q] = items
    print(f"\n=== Query: {q} ===")
    for r in items:
        license_name = r.get("license", {})
        license_spdx = license_name.get("spdx_id", "N/A") if license_name else "N/A"
        desc = (r.get("description") or "")[:120]
        print(f"{r['full_name']} | ⭐{r['stargazers_count']} | {license_spdx} | {r.get('language','')} | {desc}")

# Save to file
with open("docs/research/github_raw_data.json", "w") as f:
    json.dump(all_results, f, indent=2)

print("\n\nData saved to docs/research/github_raw_data.json")