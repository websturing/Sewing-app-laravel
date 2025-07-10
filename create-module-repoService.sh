#!/bin/bash

read -p "Masukkan nama module (Contoh: RoleManagement, role management): " raw_module

# =========== Normalisasi Nama ===========
# 1. Hapus karakter non-alfanumerik, ubah ke lowercase dengan dash
slug_name=$(echo "$raw_module" | tr '[:upper:]' '[:lower:]' | sed -E 's/[^a-z0-9]+/_/g')

# 2. PascalCase untuk class dan file
ModuleName=$(echo "$slug_name" | sed -E 's/(^|_)([a-z])/\U\2/g')

# 3. camelCase untuk variabel
LowerName=$(echo "$ModuleName" | sed -E 's/^([A-Z])/\L\1/')

# 4. lowercase folder
folder_name="$slug_name"

# === Paths ===
repo_path="app/Repositories/$folder_name"
service_path="app/Services/$folder_name"
repo_provider="app/Providers/RepositoryServiceProvider.php"
service_provider="app/Providers/ServiceServiceProvider.php"

mkdir -p "$repo_path"
mkdir -p "$service_path"

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

use App\Services\\$ModuleName\\${ModuleName}ServiceInterface;
use App\Repositories\\$ModuleName\\${ModuleName}RepositoryInterface;

class ${ModuleName}Service implements ${ModuleName}ServiceInterface
{
    protected \$$LowerName"Repository;

    public function __construct(${ModuleName}RepositoryInterface \$$LowerName"Repository)
    {
        \$this->{$LowerName}Repository = \$$LowerName"Repository;
    }

    public function getAll${ModuleName}()
    {
        return \$this->{$LowerName}Repository->all();
    }
}
EOL

# === Helper: Inject Binding ===
inject_binding_block() {
    local file="$1"
    local line="$2"
    local bind="$3"
    local tmpfile="$(mktemp)"

    if ! grep -q "$bind" "$file"; then
        awk -v line="$line" -v bind="$bind" '
            $0 ~ line {
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

# === Helper: Tambahkan AUTO-BINDINGS jika belum ada
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

# === Binding Repository
ensure_autobind_block "$repo_provider"
inject_binding_block "$repo_provider" "// AUTO-BINDINGS BELOW" "        \$this->app->bind(\\App\\Repositories\\${ModuleName}\\${ModuleName}RepositoryInterface::class, \\App\\Repositories\\${ModuleName}\\${ModuleName}Repository::class);"

# === Binding Service
ensure_autobind_block "$service_provider"
inject_binding_block "$service_provider" "// AUTO-BINDINGS BELOW" "        \$this->app->bind(\\App\\Services\\${ModuleName}\\${ModuleName}ServiceInterface::class, \\App\\Services\\${ModuleName}\\${ModuleName}Service::class);"

echo "🎉 Modul '$ModuleName' berhasil dibuat di folder '$folder_name'!"
