<nav class="bg-white border-b border-[#CCCCCC] p-4 flex justify-between items-center">
    <div>
        <a href="{{ route('home') }}" class="text-xl font-bold text-[#1A73E8]">ServisTahografa</a>
    </div>
    <div class="space-x-4">
        <a href="{{ route('home') }}" class="text-gray-700 hover:text-[#1A73E8]">Home / Moji servisi</a>
        <a href="{{ route('servis.createZakazi') }}" class="text-gray-700 hover:text-[#1A73E8]">Zakazivanje servisa</a>
        <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-[#1A73E8]">Profil</a>
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-gray-700 hover:text-[#1A73E8]">Logout</button>
        </form>
    </div>
</nav>
