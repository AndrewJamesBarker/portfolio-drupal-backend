ICON_DIR="web/sites/default/files/skills-icons"

for f in "$ICON_DIR"/*.png; do
  filename=$(basename "$f")
  name=$(basename "$f" .png | sed -E 's/[-_]/ /g' | awk '{for(i=1;i<=NF;++i) $i=toupper(substr($i,1,1)) substr($i,2)}1')
  echo "  - name: \"$name\""
  echo "    filename: \"$filename\""
done
