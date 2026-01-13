<nav class="bg-white border-b border-[#CCCCCC] p-4 flex justify-between items-center">
    <div>
        <a href="{{ route('serviser.dashboard') }}" class="text-xl font-bold text-[#1A73E8]">ServisTahografa</a>
    </div>
    <div class="space-x-4">
        <a href="{{ route('serviser.dashboard') }}" class="text-gray-700 hover:text-[#1A73E8]">Dashboard</a>
        <a href="{{ route('serviser.servisni-zahtevi') }}" class="text-gray-700 hover:text-[#1A73E8]">Servisni zahtevi</a>
        <a href="{{ route('serviser.aktivni-servisi') }}" class="text-gray-700 hover:text-[#1A73E8]">Aktivni servisi</a>
        <a href="{{ route('serviser.popravke') }}" class="text-gray-700 hover:text-[#1A73E8]">Popravke</a>
        <a href="{{ route('serviser.zalihe') }}" class="text-gray-700 hover:text-[#1A73E8]">Zalihe</a>
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-gray-700 hover:text-[#1A73E8]">Logout</button>
        </form>
    </div>
</nav>