<div id="modalAturan"
    style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border: 1px solid #ccc; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); z-index: 1001; width: 80%; max-width: 600px; text-align: left; border-radius: 10px;">
    <h2>Aturan Kuis</h2>
    <ul>
        <li>Kuis terdiri dari 10 soal pilihan ganda.</li>
        <li>Setiap soal bernilai 10 poin.</li>
        <li>Anda harus mendapatkan minimal 70 poin untuk melanjutkan ke level berikutnya.</li>
    </ul>
    <button id="mulaiKuisSebenarnyaBtn"
        style="padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #007bff; color: white; border: none; border-radius: 5px; margin-right: 10px; transition: background-color 0.3s ease;">Mulai</button>
    <button id="tutupModalBtn"
        style="padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #ddd; color: #333; border: 1px solid #ccc; border-radius: 5px; transition: background-color 0.3s ease;">Tutup</button>
</div>

<div id="modalOverlay"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;">
</div>
<link rel="stylesheet" href="{{ asset('css/modalkuis.css') }}">
