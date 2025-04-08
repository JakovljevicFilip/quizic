ACTUAL_USER=${USER:-$(whoami)}
echo "👤 Applying ACLs for user: $ACTUAL_USER"

mkdir -p storage/logs bootstrap/cache storage/framework/sessions storage/framework/views

dirs=(
  "storage"
  "storage/logs"
  "storage/framework/sessions"
  "storage/framework/views"
  "bootstrap/cache"
)

for dir in "${dirs[@]}"; do
  if [ -d "$dir" ]; then
    echo "🛠️  Fixing permissions for $dir"
    chown -R 33:33 "$dir"
    setfacl -Rm u:$ACTUAL_USER:rwx,d:u:$ACTUAL_USER:rwx "$dir"
  else
    echo "⚠️  Skipping $dir (still does not exist)"
  fi
done
