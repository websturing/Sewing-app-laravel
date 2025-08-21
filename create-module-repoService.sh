#!/bin/bash

read -p "Masukkan nama module (Contoh: RoleManagement, role management): " raw_module

# =========== Normalisasi Nama ===========
# 1. Slug lowercase dengan underscore
slug_name=$(echo "$raw_module" | tr '[:upper:]' '[:lower:]' | sed -E 's/[^a-z0-9]+/_/g')

# 2. PascalCase untuk class dan folder (PSR-4)
ModuleName=$(echo "$slug_name" | sed -E 's/(^|_)([a-z])/\U\2/g')

# 3. camelCase untuk variabel
LowerName=$(echo "$ModuleName" | sed -E 's/^([A-Z])/\L\1/')

# === Paths === (PascalCase folder agar PSR-4 sesuai)
repo_path="app/Repositories/$ModuleName"
service_path="app/Services/$ModuleName"
repo_provider="app/Providers/RepositoryServiceProvider.php"
service_provider="app/Providers/ServiceServiceProvider.php"

mkdir -p "$repo_path"
mkdir -p "$service_path"

# === Generate Model jika belum ada ===
model_path="app/Models/${ModuleName}.php"
if [ ! -f "$model_path" ]; then
    echo "📦 Membuat model $ModuleName..."
    php artisan make:model "$ModuleName" >/dev/null 2>&1
fi

# === Repository Interface ===
cat > "$repo_path/${ModuleName}RepositoryInterface.php" <<EOL
<?php

namespace App\Repositories\\$ModuleName;

interface ${ModuleName}RepositoryInterface
{
    public function all();
}
EOL

# === Repository Implementation ===
cat > "$repo_path/${ModuleName}Repository.php" <<EOL
<?php

namespace App\Repositories\\$ModuleName;

use App\Models\\${ModuleName};

class ${ModuleName}Repository implements ${ModuleName}RepositoryInterface
{
    public function all()
    {
        return ${ModuleName}::all();
    }
}
EOL

# === Service Interface ===
cat > "$service_path/${ModuleName}ServiceInterface.php" <<EOL
<?php

namespace App\Services\\$ModuleName;

interface ${ModuleName}ServiceInterface
{
    public function getAll${ModuleName}();
}
EOL

# === Service Implementation ===
cat > "$service_path/${ModuleName}Service.php" <<EOL
<?php

namespace App\Services\\$ModuleName;

use App\Repositories\\$ModuleName\\${ModuleName}RepositoryInterface;

class ${ModuleName}Service implements ${ModuleName}ServiceInterface
{
    protected \$${LowerName}Repository;

    public function __construct(${ModuleName}RepositoryInterface \$${LowerName}Repository)
    {
        \$this->${LowerName}Repository = \$${LowerName}Repository;
    }

    public function getAll${ModuleName}()
    {
        return \$this->${LowerName}Repository->all();
    }
}
EOL

# === Helper: Inject Binding Aman ===
inject_binding_block() {
    local file="$1"
    local marker="$2"
    local bind="$3"
    local tmpfile="$(mktemp)"

    if ! grep -qF "$bind" "$file"; then
        awk -v marker="$marker" -v bind="$bind" '
            index(\$0, marker) {
                print
                print bind
                next
            }
            { print }
        ' "$file" > "$tmpfile" && mv "$tmpfile" "$file"
        echo "✅ Binding ditambahkan: $bind"
    else
        echo "ℹ️  Binding sudah ada, dilewati."
    fi
}

# === Helper: Tambahkan Marker jika belum ada ===
ensure_autobind_block() {
    local file="$1"
    if ! grep -q "// AUTO-BINDINGS BELOW" "$file"; then
        awk '
            /public function register\(\)/ {
                print
                getline nextLine
                print nextLine
                print "        // AUTO-BINDINGS BELOW"
                next
            }
            { print }
        ' "$file" > temp && mv temp "$file"
    fi
}

# === Binding Repository ===
ensure_autobind_block "$repo_provider"
inject_binding_block "$repo_provider" "// AUTO-BINDINGS BELOW" "        \$this->app->bind(\\App\\Repositories\\${ModuleName}\\${ModuleName}RepositoryInterface::class, \\App\\Repositories\\${ModuleName}\\${ModuleName}Repository::class);"

# === Binding Service ===
ensure_autobind_block "$service_provider"
inject_binding_block "$service_provider" "// AUTO-BINDINGS BELOW" "        \$this->app->bind(\\App\\Services\\${ModuleName}\\${ModuleName}ServiceInterface::class, \\App\\Services\\${ModuleName}\\${ModuleName}Service::class);"

echo "🎉 Modul '$ModuleName' berhasil dibuat di:"
echo "   📂 $repo_path"
echo "   📂 $service_path"
