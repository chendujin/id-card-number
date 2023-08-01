<?php
namespace Chendujin\IdCardNumber;

/**
 * 校验身份证是否合法
 * Class IDCard
 * @package Church\IDCard
 */
class IdCardNumber
{
    protected string $id;

    protected string $code = '';

    protected static mixed $regions = [];

    /**
     * 系数
     * @var int[]
     */
    protected array $coefficient = [
        7,
        9,
        10,
        5,
        8,
        4,
        2,
        1,
        6,
        3,
        7,
        9,
        10,
        5,
        8,
        4,
        2
    ];

    /**
     * 余数列表
     * @var array
     */
    protected array $remainder = [
        1,
        0,
        'X',
        9,
        8,
        7,
        6,
        5,
        4,
        3,
        2
    ];

    /**
     * IDCard constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->setId($id);
    }

    /**
     * @param mixed $id
     */
    public function setId(mixed $id): void
    {
        $this->id = strtoupper($id);
    }

    /**
     * 身份证是否有效
     * @return bool
     */
    public function isValid(): bool
    {
        if (strlen($this->id) != 18) {
            return false;
        }

        $regions = self::getRegionCode();
        $code = $this->getCode();
        $remainder = $this->getRemainder();
        $CRC = $this->getCRC();

        if (isset($regions[$code]) && $CRC == $this->remainder[$remainder]) {
            return true;
        }

        return false;
    }

    /**
     * 获取行政代码
     * @return string
     */
    public function getCode(): string
    {
        if (!$this->code) {
            $this->code = substr($this->id, 0, 6);
        }

        return $this->code;
    }

    /**
     * 获取生日
     * @return string
     */
    public function getBirthday(): string
    {
        return substr($this->id, 6, 8);
    }

    /**
     * 获取顺序码
     * @return string
     */
    public function getSequenceCode(): string
    {
        return substr($this->id, 14, 3);
    }

    /**
     * 获取校验码
     * @return string
     */
    public function getCRC(): string
    {
        return substr($this->id, 17, 1);
    }

    /**
     * 获取行政代码对应的省市地址
     * @return string
     */
    public function getAddress(): string
    {
        $regions = self::getRegionCode();
        [$provinceCode, $cityCode] = $this->getProvinceAndCityCode();
        $province = $regions[$provinceCode] ?? '';
        $city = $regions[$cityCode] ?? '';
        return $province . $city;
    }

    /**
     * 获取行政代码对应的省市区地址
     * @return string
     */
    public function getAreaAddress(): string
    {
        $regions = self::getRegionCode();
        $code = $this->getCode();
        [$provinceCode, $cityCode] = $this->getProvinceAndCityCode();
        $province = $regions[$provinceCode] ?? '';
        $city = $regions[$cityCode] ?? '';
        $area = $regions[$code] ?? '';
        return $province . $city . $area;
    }

    /**
     * 根据身份证号，自动返回对应的省、自治区、直辖市代
     * @return string
     */
    public function getProvince(): string
    {
        $regions = self::getRegionCode();
        [$provinceCode, $cityCode] = $this->getProvinceAndCityCode();
        return $regions[$provinceCode] ?? '';
    }

    /**
     * 从身份证号中获取性别
     * @return string
     */
    public function getSex(): string
    {
        $sexBit = intval(substr($this->id, 16, 1));
        return is_float($sexBit / 2) ? '男' : '女';
    }

    /**
     * 根据身份证号，自动返回对应的星座
     * @return string
     */
    public function getIDCardXZ(): string
    {
        $bir = substr($this->id,10,4);
        $month = (int)substr($bir,0,2);
        $day = (int)substr($bir,2);
        $strValue = '';
        if(($month == 1 && $day <= 21) || ($month == 2 && $day <= 19)) {
            $strValue = "水瓶座";
        } elseif (($month == 2 && $day > 20) || ($month == 3 && $day <= 20)) {
            $strValue = "双鱼座";
        } elseif (($month == 3 && $day > 20) || ($month == 4 && $day <= 20)) {
            $strValue = "白羊座";
        } elseif (($month == 4 && $day > 20) || ($month == 5 && $day <= 21)) {
            $strValue = "金牛座";
        } elseif (($month == 5 && $day > 21) || ($month == 6 && $day <= 21)) {
            $strValue = "双子座";
        } elseif (($month == 6 && $day > 21) || ($month == 7 && $day <= 22)) {
            $strValue = "巨蟹座";
        } elseif (($month == 7 && $day > 22) || ($month == 8 && $day <= 23)) {
            $strValue = "狮子座";
        } elseif (($month == 8 && $day > 23) || ($month == 9 && $day <= 23)) {
            $strValue = "处女座";
        } elseif (($month == 9 && $day > 23) || ($month == 10 && $day <= 23)) {
            $strValue = "天秤座";
        } elseif (($month == 10 && $day > 23) || ($month == 11 && $day <= 22)) {
            $strValue = "天蝎座";
        } elseif (($month == 11 && $day > 22) || ($month == 12 && $day <= 21)) {
            $strValue = "射手座";
        } elseif (($month == 12 && $day > 21) || ($month == 1 && $day <= 20)) {
            $strValue = "魔羯座";
        }
        return $strValue;
    }

    /**
     * 根据身份证号，自动返回对应的生肖
     * @return string
     */
    public function getIDCardSX(): string
    {
        $start = 1901;
        $end = (int)substr($this->id,6,4);
        $x = ($start - $end) % 12;
        $value = "";
        if($x == 1 || $x == -11){$value = "鼠";}
        if($x == 0) {$value = "牛";}
        if($x == 11 || $x == -1){$value = "虎";}
        if($x == 10 || $x == -2){$value = "兔";}
        if($x == 9 || $x == -3){$value = "龙";}
        if($x == 8 || $x == -4){$value = "蛇";}
        if($x == 7 || $x == -5){$value = "马";}
        if($x == 6 || $x == -6){$value = "羊";}
        if($x == 5 || $x == -7){$value = "猴";}
        if($x == 4 || $x == -8){$value = "鸡";}
        if($x == 3 || $x == -9){$value = "狗";}
        if($x == 2 || $x == -10){$value = "猪";}
        return $value;
    }

    /**
     * 获取行政代码数组
     * @return array
     */
    public static function getRegionCode(): array
    {
        if (! self::$regions) {
            self::$regions = file_get_contents(dirname(__FILE__) . '/../data/region.json');
            self::$regions = json_decode(self::$regions, true);
        }

        return self::$regions;
    }

    /**
     * 计算校验码
     * @return int
     */
    protected function getRemainder(): int
    {
        $sum = 0;
        $length = 17;
        $data = substr($this->id, 0, $length);

        for ($i = 0; $i < $length; $i++) {
            $sum += intval($data[$i]) * $this->coefficient[$i];
        }

        return $sum % 11;
    }

    /**
     * 获取省和市的行政代码
     * @return string[]
     */
    protected function getProvinceAndCityCode(): array
    {
        $code = $this->getCode();

        $cityCode = substr($code, 0, 4) . '00';
        $provinceCode = substr($code, 0, 2) . '0000';

        return [$provinceCode, $cityCode];
    }
}
