import json, sys
data = json.load(sys.stdin)
for p in data:
    name = p.get("post_name", "")
    title = p.get("post_title", "").lower()
    if "cmlp" in name or "cmlp" in title:
        print(p["ID"], name, p["post_title"])
