<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CulturalContent;

class CulturalContentSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------------------------------------------
        // 1. STORIES (Cerita Rakyat Nusantara)
        // ----------------------------------------------------
        CulturalContent::updateOrCreate([
            'title' => 'Legenda Roro Jonggrang & Candi Sewu',
            'type' => 'story',
        ], [
            'language' => 'Jawa',
            'excerpt' => 'Kisah asal-usul Candi Prambanan dan Candi Sewu yang dibangun dalam waktu satu malam oleh Bandung Bondowoso.',
            'image_url' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=800&q=80',
            'full_content' => "Dahulu kala di Jawa Tengah, terdapat dua kerajaan tetangga, yaitu Kerajaan Pengging dan Kerajaan Baka. Kerajaan Pengging dipimpin oleh Raja Damar Maya yang memiliki putra sakti bernama Bandung Bondowoso. Sementara Kerajaan Baka dipimpin oleh Prabu Baka, seorang raksasa yang memiliki putri cantik jelita bernama Roro Jonggrang.\n\nDalam sebuah peperangan, Bandung Bondowoso berhasil mengalahkan Kerajaan Baka dan membunuh Prabu Baka. Saat melihat Roro Jonggrang, Bandung Bondowoso jatuh hati dan ingin menjadikannya permaisuri.\n\nRoro Jonggrang yang tidak ingin menikahi pembunuh ayahnya memberikan syarat sulit: membuat 1.000 candi dan 2 sumur dalam waktu satu malam. Bandung Bondowoso mengerahkan pasukan jin dan makhluk halus untuk membangun candi tersebut.\n\nMelihat pembangunan yang hampir selesai menjelang fajar, Roro Jonggrang menumbuk padi dan membakar jerami di timur agar ayam jantan berkokok, menandakan pagi telah tiba. Pasukan jin lari ketakutan meninggalkan 999 candi.\n\nSaat tahu dirinya dicurangi, Bandung Bondowoso mengutuk Roro Jonggrang menjadi arca candi ke-1000 untuk melengkapi Candi Sewu (Prambanan).",
            'category' => 'Legenda Jawa',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        CulturalContent::updateOrCreate([
            'title' => 'Legenda Sangkuriang & Tangkuban Perahu',
            'type' => 'story',
        ], [
            'language' => 'Sunda',
            'excerpt' => 'Kisah tragis pemuda Sangkuriang dan ibunya Dayang Sumbi yang melahirkan Gunung Tangkuban Perahu di Jawa Barat.',
            'image_url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
            'full_content' => "Dahulu kala di Priangan, Jawa Barat, hidup seorang putri bernama Dayang Sumbi yang memiliki putra bernama Sangkuriang. Suatu hari, Sangkuriang berburu bersama anjing kesayangannya, Si Tumang, yang sebenarnya adalah titisan dewa.\n\nKarena suatu perselisihan saat berburu, Sangkuriang tidak sengaja membunuh Tumang. Dayang Sumbi yang sangat marah memukul kepala Sangkuriang hingga berbekas dan mengusirnya.\n\nBertahun-tahun kemudian, Sangkuriang tumbuh menjadi pemuda gagah dan bertemu kembali dengan Dayang Sumbi tanpa menyadari bahwa perempuan itu adalah ibunya (karena Dayang Sumbi dianugerahi awet muda). Saat hendak menikah, Dayang Sumbi melihat bekas luka di kepala Sangkuriang dan menyadari identitas sebenarnya.\n\nUntuk menggagalkan pernikahan, Dayang Sumbi meminta Sangkuriang membendung sungai Citarum dan membuat perahu besar sebelum terbit fajar. Ketika syarat hampir terpenuhi dengan bantuan ghaib, Dayang Sumbi menggelar kain sutra merah di timur agar fajar nampak datang lebih cepat.\n\nSangkuriang yang murka menendang perahu buatannya hingga terbalik dan membeku menjadi Gunung Tangkuban Perahu.",
            'category' => 'Legenda Sunda',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        CulturalContent::updateOrCreate([
            'title' => 'Kisah Timun Mas & Raksasa',
            'type' => 'story',
        ], [
            'language' => 'Jawa',
            'excerpt' => 'Petualangan pemberani anak perempuan bernama Timun Mas melawan raksasa jahat dengan bekal 4 benda ajaib.',
            'image_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
            'full_content' => "Di sebuah desa di Jawa, seorang janda bernama Mbok Srini sangat mendambakan anak. Ia meminta petunjuk pada raksasa bernama Buto Ijo. Raksasa memberinya biji mentimun ajaib yang akan melahirkan bayi perempuan, dengan syarat saat anak itu berusia 17 tahun harus diserahkan kepadanya.\n\nLahirlah bayi cantik yang dinamai Timun Mas. Ketika berumur 17 tahun, Buto Ijo datang menagih janji. Mbok Srini memberi Timun Mas kantong ajaib berisi biji mentimun, jarum, garam, dan terasi.\n\nSaat dikejar Buto Ijo, Timun Mas melemparkan biji mentimun yang berubah menjadi ladang mentimun luas melilit raksasa. Jarum berubah menjadi hutan bambu lebat, garam menjadi lautan luas, dan terasi menjadi lautan lumpur mendidih yang menenggelamkan Buto Ijo.\n\nTimun Mas pun kembali ke rumah dan hidup bahagia bersama ibunya.",
            'category' => 'Dongeng Nusantara',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // ----------------------------------------------------
        // 2. PROVERBS (Paribasan / Peribahasa)
        // ----------------------------------------------------
        CulturalContent::updateOrCreate([
            'text' => 'Alon-alon waton kelakon',
            'type' => 'proverb',
        ], [
            'language' => 'Jawa',
            'translation' => 'Pelan-pelan asalkan berhasil / tercapai.',
            'explanation' => 'Filosofi hidup Jawa yang mengajarkan kehati-hatian, ketelitian, dan kesabaran dalam mencapai cita-cita ketimbang terburu-buru namun memicu kegagalan.',
            'category' => 'Paribasan Jawa',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        CulturalContent::updateOrCreate([
            'text' => 'Urip iku urup',
            'type' => 'proverb',
        ], [
            'language' => 'Jawa',
            'translation' => 'Hidup itu hendaknya memberi nyala (manfaat).',
            'explanation' => 'Prinsip hidup bahwa keberadaan manusia di dunia hendaknya memberi manfaat bagi sesama, lingkungan, dan masyarakat sekitar.',
            'category' => 'Falsafah Jawa',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        CulturalContent::updateOrCreate([
            'text' => 'Cikaracak deepan ka batu lila-lila jadi legok',
            'type' => 'proverb',
        ], [
            'language' => 'Sunda',
            'translation' => 'Tetesan air yang terus menerus mengenai batu lama-lama akan berlubang.',
            'explanation' => 'Usaha kecil yang dilakukan secara terus-menerus dan konsisten pasti akan menghasilkan perubahan besar dan keberhasilan.',
            'category' => 'Babasan Sunda',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        CulturalContent::updateOrCreate([
            'text' => 'Mikul dhuwur mendhem jero',
            'type' => 'proverb',
        ], [
            'language' => 'Jawa',
            'translation' => 'Memikul tinggi-tinggi dan memendam dalam-dalam.',
            'explanation' => 'Nasihat untuk senantiasa menjunjung tinggi kehormatan orang tua/pemimpin dan menutupi aib serta keburukan mereka.',
            'category' => 'Paribasan Jawa',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // ----------------------------------------------------
        // 3. TRIVIA (Fakta Unik Kebudayaan)
        // ----------------------------------------------------
        CulturalContent::updateOrCreate([
            'fact' => 'Aksara Jawa (Hanacaraka) terdiri dari 20 huruf dasar yang membentuk sebuah bait puisi berunsur cerita tentang dua utusan bernama Dora dan Sembada.',
            'type' => 'trivia',
        ], [
            'language' => 'Jawa',
            'category' => 'Aksara Nusantara',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        CulturalContent::updateOrCreate([
            'fact' => 'Angklung Sunda telah diakui oleh UNESCO sebagai Karya Agung Warisan Budaya Lisan dan Non-bendawi Manusia sejak November 2010.',
            'type' => 'trivia',
        ], [
            'language' => 'Sunda',
            'category' => 'Kesenian Tradisional',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        CulturalContent::updateOrCreate([
            'fact' => 'Bahasa Jawa memiliki tingkatan tutur (Undha-Usuk Basa) paling detail di Nusantara, mencakup Ngoko, Krama Madya, dan Krama Inggil/Hinggil.',
            'type' => 'trivia',
        ], [
            'language' => 'Jawa',
            'category' => 'Linguistik',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        CulturalContent::updateOrCreate([
            'fact' => 'Sistem kekerabatan masyarakat Batak menggunakan Dalihan Na Tolu (Somba Marhula-hula, Elek Marboru, Manat Mardongan Tubu) sebagai pilar adat istiadat.',
            'type' => 'trivia',
        ], [
            'language' => 'Batak',
            'category' => 'Adat Istiadat',
            'is_active' => true,
            'sort_order' => 4,
        ]);
    }
}
