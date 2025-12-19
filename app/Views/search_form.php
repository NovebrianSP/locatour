<!-- Search Section - Insert after Stats Section and before Featured Section -->
<section id="search" class="search-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px 0; color: white; position: relative; z-index: 5; margin-bottom: -40px;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="margin-bottom: 10px;">Cari Destinasi Wisata</h2>
            <p style="opacity: 0.9; font-size: 1.1rem;">Gunakan keyword, filter harga & kategori, dengan pertimbangan cuaca</p>
        </div>
        <div style="background: white; border-radius: 15px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <h3 style="color: #2c3e50; margin-bottom: 30px;"><i class="fas fa-search"></i> Pencarian Canggih</h3>
            <div class="row">
                <div class="col-md-6">
                    <div style="margin-bottom: 20px;">
                        <label for="searchKeyword" style="font-weight: 600; color: #2c3e50; margin-bottom: 10px; display: block;">Kata Kunci</label>
                        <input type="text" id="searchKeyword" class="form-control" placeholder="Contoh: candi, pantai, air terjun..." style="border: 2px solid #ddd; border-radius: 8px; padding: 12px; font-size: 1rem;">
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="margin-bottom: 20px;">
                        <label for="searchCategory" style="font-weight: 600; color: #2c3e50; margin-bottom: 10px; display: block;">Kategori</label>
                        <select id="searchCategory" class="form-control" style="border: 2px solid #ddd; border-radius: 8px; padding: 12px; font-size: 1rem;">
                            <option value="">-- Semua Kategori --</option>
                            <option value="cagar alam">Cagar Alam</option>
                            <option value="budaya">Budaya</option>
                            <option value="taman hiburan">Taman Hiburan</option>
                            <option value="bahari">Bahari</option>
                            <option value="wisata air">Wisata Air</option>
                            <option value="museum">Museum</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 10px; display: block;">Rentang Harga</label>
                        <div style="display: flex; gap: 15px;">
                            <input type="number" id="searchMinPrice" class="form-control" placeholder="Min (Rp)" min="0" step="5000" style="border: 2px solid #ddd; border-radius: 8px; padding: 12px; font-size: 1rem;">
                            <input type="number" id="searchMaxPrice" class="form-control" placeholder="Max (Rp)" min="0" step="5000" style="border: 2px solid #ddd; border-radius: 8px; padding: 12px; font-size: 1rem;">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="margin-bottom: 20px;">
                        <label for="searchPref" style="font-weight: 600; color: #2c3e50; margin-bottom: 10px; display: block;">Preferensi Cuaca</label>
                        <select id="searchPref" class="form-control" style="border: 2px solid #ddd; border-radius: 8px; padding: 12px; font-size: 1rem;">
                            <option value="">-- Sesuai Cuaca Terkini --</option>
                            <option value="indoor">Indoor</option>
                            <option value="outdoor">Outdoor</option>
                            <option value="mixed">Campuran</option>
                        </select>
                    </div>
                </div>
            </div>
            <button class="btn" onclick="performSearch()" style="background: #e74c3c; color: white; padding: 12px 40px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem; width: 100%; margin-top: 20px;">
                <i class="fas fa-search"></i> Cari Destinasi
            </button>
        </div>
    </div>
</section>

<script>
function performSearch() {
    const keyword = document.getElementById('searchKeyword').value;
    const category = document.getElementById('searchCategory').value;
    const minPrice = document.getElementById('searchMinPrice').value;
    const maxPrice = document.getElementById('searchMaxPrice').value;
    const pref = document.getElementById('searchPref').value;

    // Build query string
    const params = new URLSearchParams();
    if (keyword) params.append('q', keyword);
    if (category) params.append('category', category);
    if (minPrice) params.append('min_price', minPrice);
    if (maxPrice) params.append('max_price', maxPrice);
    if (pref) params.append('pref', pref);

    // Redirect to search results page
    window.location.href = '/locatour/search?' + params.toString();
}

// Allow Enter key to trigger search
document.getElementById('searchKeyword').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') performSearch();
});
</script>
