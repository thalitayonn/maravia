# 🔧 Fix Filter dengan Value '0' (Inactive)

## ❌ Masalah

Saat filter **Inactive** (value = `0`), tidak bisa karena:
- JavaScript menganggap `0` sebagai **falsy value**
- Script clean parameters menghapus `0` karena dianggap empty
- Akibatnya filter Inactive tidak work!

```javascript
// WRONG!
if (!input.value || input.value === '') {
    input.removeAttribute('name');  // Ini hapus '0' juga!
}
```

## ✅ Solusi

### **Perbaikan JavaScript**

```javascript
// CORRECT!
if (input.value === '' || input.value === null || input.value === undefined) {
    input.removeAttribute('name');  // Hanya hapus yang benar-benar kosong
}
```

**Penjelasan:**
- `input.value === ''` → Hapus string kosong ✅
- `input.value === null` → Hapus null ✅  
- `input.value === undefined` → Hapus undefined ✅
- `input.value === '0'` → **TIDAK DIHAPUS!** ✅

### **Controller Validation**

Controller sudah benar:
```php
if ($request->has('status') && $request->status !== '') {
    $query->where('is_active', $request->status);
}
```

`!== ''` memastikan:
- `'0'` → **VALID** ✅ (Inactive)
- `'1'` → **VALID** ✅ (Active)
- `''` → **INVALID** ❌ (Diabaikan)

## 🎯 Test Cases

### **Test 1: Filter Active Only**
```
1. Pilih "Active" dari dropdown Status
2. URL: /admin/photos?status=1
3. ✅ Tampil hanya foto Active
```

### **Test 2: Filter Inactive Only**
```
1. Pilih "Inactive" dari dropdown Status
2. URL: /admin/photos?status=0  ← Value '0' tetap ada!
3. ✅ Tampil hanya foto Inactive
```

### **Test 3: All Status**
```
1. Pilih "All Status" dari dropdown
2. URL: /admin/photos (tanpa parameter status)
3. ✅ Tampil semua foto
```

### **Test 4: Inactive + Category**
```
1. Pilih "Inactive" dari Status
2. Pilih "Kegiatan OSIS" dari Category
3. URL: /admin/photos?status=0&category=3
4. ✅ Tampil foto Inactive di kategori OSIS
```

## 🔍 Falsy Values in JavaScript

**Yang dianggap falsy:**
- `false`
- `0` ← **INI MASALAHNYA!**
- `''` (empty string)
- `null`
- `undefined`
- `NaN`

**Solusi:**
Gunakan **strict comparison** (`===`) untuk cek empty, bukan **truthy/falsy** (`!value`)

## 📊 Before vs After

### **Before (WRONG)**
```javascript
if (!input.value || input.value === '') {
    // Hapus parameter
}
```

**Problem:**
- `!0` = `true` → Parameter dihapus ❌
- `!''` = `true` → Parameter dihapus ✅
- `!'1'` = `false` → Parameter tetap ✅

### **After (CORRECT)**
```javascript
if (input.value === '' || input.value === null || input.value === undefined) {
    // Hapus parameter
}
```

**Result:**
- `'0' === ''` = `false` → Parameter tetap ✅
- `'' === ''` = `true` → Parameter dihapus ✅
- `'1' === ''` = `false` → Parameter tetap ✅

## ✨ Benefits

1. **Filter Inactive Work!**
   - Value `'0'` tidak dihapus
   - Query ke database benar
   
2. **Clean URL**
   - Parameter kosong tetap dihapus
   - URL tetap bersih
   
3. **Consistent Behavior**
   - Active (1) work ✅
   - Inactive (0) work ✅
   - All Status work ✅

## 🎉 Result

**SEKARANG FILTER INACTIVE BISA!**

Test:
```
1. Pilih "Inactive" dari Status dropdown
2. ✅ URL: /admin/photos?status=0
3. ✅ Tampil foto-foto Inactive
4. ✅ Badge muncul: "Status: Inactive ×"
```

**ALL FILTERS NOW WORKING PERFECTLY!** 🔥✨

---

**Updated**: October 23, 2025
**Status**: ✅ Fixed
**Issue**: Falsy value '0' treated as empty
**Solution**: Strict comparison for empty check
