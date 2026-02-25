<div id="badgeAwardedModal" class="modal-badge-custom" style="display: none;">
    <div class="modal-content-badge-custom">
        <span class="close-badge-custom">&times;</span>
        <h3 class="modal-title-badge-custom">Selamat! Kamu Mendapatkan Lencana Baru!</h3>
        <div class="badge-info-container">
            <img id="badgeImage" src="" alt="Lencana Baru" class="awarded-badge-image">
            <div class="badge-text-details">
                <p class="badge-name" id="badgeName"></p>
                <p class="badge-description" id="badgeDescription"></p>
                <p class="badge-date" id="badgeDate"></p>
            </div>
        </div>
        <button class="modal-button-badge-custom" onclick="closeBadgeModal()">Oke!</button>
    </div>
</div>

<style>
    /* CSS Dasar untuk Modal Box (Sesuaikan dengan desain Anda!) */
    .modal-badge-custom {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1000;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.7);
        /* Black w/ opacity */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content-badge-custom {
        background-color: #fefefe;
        margin: auto;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        text-align: center;
        width: 90%;
        max-width: 500px;
        position: relative;
        animation: fadeIn 0.3s ease-out;
    }

    .close-badge-custom {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-badge-custom:hover,
    .close-badge-custom:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-title-badge-custom {
        color: #4CAF50;
        /* Warna hijau menarik */
        font-size: 1.8em;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .badge-info-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .awarded-badge-image {
        width: 120px;
        /* Ukuran gambar lencana di modal */
        height: 120px;
        object-fit: contain;
        border-radius: 50%;
        border: 5px solid #FFD700;
        /* Border emas */
        box-shadow: 0 0 15px rgba(255, 215, 0, 0.5);
        /* Efek glow emas */
    }

    .badge-text-details {
        text-align: center;
    }

    .badge-name {
        font-size: 1.5em;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }

    .badge-description {
        font-size: 1em;
        color: #666;
        margin-bottom: 5px;
    }

    .badge-date {
        font-size: 0.9em;
        color: #999;
    }

    .modal-button-badge-custom {
        background-color: #4CAF50;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.1em;
        transition: background-color 0.3s ease;
    }

    .modal-button-badge-custom:hover {
        background-color: #45a049;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
