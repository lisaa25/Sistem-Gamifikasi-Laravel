// JavaScript untuk menutup notifikasi secara otomatis setelah beberapa detik
document.addEventListener("DOMContentLoaded", function () {
    var notification = document.querySelector(".notification");

    // Cek apakah ada notifikasi yang ditampilkan
    if (notification) {
        // Tambahkan event listener untuk menutup notifikasi setelah beberapa detik
        setTimeout(function () {
            notification.style.display = "none";
        }, 5000); // Notifikasi akan hilang setelah 5 detik (5000 milidetik)
    }
});
