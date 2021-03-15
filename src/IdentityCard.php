<?php

namespace liuyuit\IdentityCard;

class IdentityCard
{
    /**
     * 身份证号.
     *
     * @var string
     */
    protected $id;

    /**
     * 行政区划代码
     *
     * @var array
     */
    protected $areaCodes;

    protected $isTrue;

    public function __construct(string $id)
    {
        $this->id = strtoupper($id);
    }

    /**
     * 验证身份号.
     *
     * @return bool
     */
    public function check(): bool
    {
        if ($this->isTrue === null) {
            $this->isTrue = $this->checkLength() && $this->checkAreaCode() && $this->checkBirthday() && $this->checkCode();
        }

        return $this->isTrue;
    }

    public function checkLength(): bool
    {
        $length = strlen($this->id);
        return $length == 18;
    }

    /**
     * 验证行政区划代码
     *
     * @return bool
     */
    public function checkAreaCode(): bool
    {
        $areaCode = substr($this->id, 0, 6);
        $provinceCode = substr($areaCode, 0, 2);

        // 根据GB/T2260—999，省市代码11到65
        if (11 <= $provinceCode && $provinceCode <= 65) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证生日.
     *
     * @return bool
     */
    public function checkBirthday(): bool
    {
        $year = substr($this->id, 6, 4);
        $month = substr($this->id, 10, 2);
        $day = substr($this->id, 12, 2);

        return checkdate($month, $day, $year);
    }

    /**
     * 验证校验码
     *
     * @return bool
     */
    public function checkCode(): bool
    {
        $weight = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $codes = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
        $validate = substr($this->id, 0, 17);
        $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            $sum += substr($validate, $i, 1) * $weight[$i];
        }

        return $codes[$sum % 11] == substr($this->id, 17, 1);
    }

    /**
     * 获取生日.
     *
     * @param string $format
     *
     * @throws InvalidIdentityCardException
     *
     * @return string
     */
    public function birthday(string $format = 'Ymd'): string
    {
        return date($format, strtotime($this->year().'-'.$this->month().'-'.$this->day()));
    }

    /**
     * 获取年.
     *
     * @throws InvalidIdentityCardException
     *
     * @return int
     */
    public function year(): int
    {
        if ($this->check() === false) {
            throw new InvalidIdentityCardException();
        }

        return (int) substr($this->id, 6, 4);
    }

    /**
     * 获取月.
     *
     * @throws InvalidIdentityCardException
     *
     * @return int
     */
    public function month(): int
    {
        if ($this->check() === false) {
            throw new InvalidIdentityCardException();
        }

        return (int) substr($this->id, 10, 2);
    }

    /**
     * 获取日.
     *
     * @throws InvalidIdentityCardException
     *
     * @return int
     */
    public function day(): int
    {
        if ($this->check() === false) {
            throw new InvalidIdentityCardException();
        }

        return (int) substr($this->id, 12, 2);
    }

    /**
     * 获取年龄.
     *
     * @throws InvalidIdentityCardException
     *
     * @return int
     */
    public function age(): int
    {
        $year = $this->year();
        $month = $this->month();
        $day = $this->day();

        $nowYear = (int) date('Y');
        $nowMonth = (int) date('n');
        $nowDay = (int) date('j');

        $age = $nowYear > $year ? $nowYear - $year - 1 : 0;
        if ($nowMonth > $month || ($nowMonth === $month && $nowDay >= $day)) {
            $age++;
        }

        return $age;
    }

    /**
     * 获取性别.
     *
     * @param int $male
     * @param int $female
     * @return string
     * @throws InvalidIdentityCardException
     */
    public function gender($male = 1, $female = 2): string
    {
        if ($this->check() === false) {
            throw new InvalidIdentityCardException();
        }

        return substr($this->id, 16, 1) % 2 ? $male : $female; //     MALE = 1; FEMALE = 2;
    }

    /**
     * 获取星座.
     *
     * @throws InvalidIdentityCardException
     *
     * @return string
     */
    public function constellation(): string
    {
        $constellation = ['水瓶座', '双鱼座', '白羊座', '金牛座', '双子座', '巨蟹座', '狮子座', '处女座', '天秤座', '天蝎座', '射手座', '魔羯座'];
        $constellationDays = [21, 20, 21, 20, 21, 22, 23, 23, 23, 24, 22, 21];

        $month = $this->month() - 1;
        $day = $this->day();

        if ($day < $constellationDays[$month]) {
            $month--;
        }

        return $month >= 0 ? $constellation[$month] : $constellation[11];
    }

    /**
     * 获取属相.
     *
     * @throws InvalidIdentityCardException
     *
     * @return string
     */
    public function zodiac(): string
    {
        $zodiac = ['牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪', '鼠'];
        $index = abs($this->year() - 1901) % 12;

        return $zodiac[$index];
    }
}
