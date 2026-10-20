<?php
final class NameGenerator
{
    private const FIRST = ['Chinedu', 'Amaka', 'Tunde', 'Ngozi', 'Ibrahim', 'Funke', 'Emeka', 'Aisha', 'Segun', 'Blessing', 'Yusuf', 'Chiamaka'];
    private const LAST  = ['Okafor', 'Adeyemi', 'Bello', 'Nwosu', 'Balogun', 'Eze', 'Musa', 'Okonkwo', 'Ogunleye', 'Abubakar', 'Ibe', 'Afolabi'];

    public static function generate(): string
    {
        return self::FIRST[array_rand(self::FIRST)] . ' ' . self::LAST[array_rand(self::LAST)];
    }
}
