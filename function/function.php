<?php
    /*
     * Проверяет наличие и (в идеале) корректность подписки. Пока не используется.
     * $login - логин пользователя
     * $pageOwner - логин владельца страницы
     * $settings - настройки подключения
     * Вернёт true, если подписка существует
     */
    function subscriptionCheck($login, $pageOwner, $settings)
    {
        $base = new mysqli($settings['host'],$settings['user'], $settings['password'], $settings['database']);
        $arrayBase = $base->query("SELECT `subscriptions`
                            FROM `new_table`
                            WHERE `login` = '".$login."'");
        $subscription = mysqli_fetch_array($arrayBase)['subscriptions'];
        $base->close();
        $arrSubscription = explode(',', $subscription);
        $bool_1 = array_search($pageOwner, $arrSubscription);
        if($bool_1 !== false)
            return true;
        else
            return false;
    }

    /*
         Вернёт количество проверяемых значений, где
            $table - имя таблицы
            $essence - имя домена в котором ведётся поиск
            $value - значение, которое ищется
            $settings - массив настроек подключения
     */
    function existence($table, $essence, $value, $settings){

        if ($value != "")
        {
            $base = new mysqli($settings['host'], $settings['user'], $settings['password'], $settings['database']);
            $count = $base->query("SELECT COUNT(`".$essence."`) 
                            FROM `".$table."` 
                            WHERE `".$essence."` = '".$value."'");
            $base->close();
            $out = mysqli_fetch_array($count);
            return $out[0];
        }
        else
            return 'error';

    }
    /*
     * Длбавляет новые записи в БД. Вернёт 1, если операция прошла успешно
     * ---------------------------------------------------------------------
     * WARNING! Данная функция работает пока только со строчными значениями!
     * ---------------------------------------------------------------------
     *  $table - таблица
     *  $essenceArr - массив имён доменов
     *  $valueArr - массив значений
     *  $settings - массив настроек подключения к БД
     * Примечание: Если в таблице присутствует домен с автоинкрементом,
     * необходимо обязательно указывать массив имён доменов $essenceArr.
     * Если такой домен отсутствует, $essenceArr указывать необязательно
     */
    function addRow($settings, $table, $valueArr, $essenceArr = false)
    {
        if($essenceArr != false)
            if(count($essenceArr) != count($valueArr))
                return false;

        //С указанием имён доменов
        if($essenceArr != false)
        {
            $domains = '(';
            $values ='(';
            for($i = 0; $i < count($valueArr); $i++)
            {
                $domains .= "`".$essenceArr[$i]."`";
                $values .= "'".$valueArr[$i]."'";
                if($i != count($valueArr) - 1)
                {
                    $domains .= ", ";
                    $values .= ", ";
                }
                else
                {
                    $domains .= ")";
                    $values .= ")";
                }
            }
        }
        //без указания имён доменов
        else
        {
            $base = new mysqli($settings['host'], $settings['user'], $settings['password'], $settings['database']);
            $result = $base->query("SELECT COUNT(*) 
                                FROM information_schema.COLUMNS 
                                WHERE TABLE_NAME='".$table."'");
            $base->close();
            $cols = mysqli_fetch_array($result)[0]; //нашли количество столбцов таблицы
            $values ='(';
            $domains = '';
            for($i = 0; $i < count($valueArr); $i++)
            {
                $values .= "'" . $valueArr[$i] . "'";

                if ($i != count($valueArr) - 1)
                    $values .= ", ";
                else
                {
                    //заполняем неуказанные поля нулями
                    for($j = count($valueArr); $j < $cols; $j++)
                        $values .= ',null';
                    $values .= ")";
                }
            }
        }
        $base = new mysqli($settings['host'], $settings['user'], $settings['password'], $settings['database']);
        $result = $base->query("INSERT INTO `".$table."` ".$domains."
                                          VALUES ".$values);
        $base->close();
        return $result;
    }

    /*
     * Проверка подключения к бд. Если $number != 0 и отсутствуют необходимые таблицы - создаёт их
     * Если $numbder = 0, проверяет
     * $settings - настройки подключения
     * $number - количество генерируемых пользователей
     * ___________________________________________
     * Не учитывает уже существующих пользователей
     */
    function createBD ($settings, $number = 0)
    {
        @$base = new mysqli($settings['host'], $settings['user'],$settings['password'],$settings['database']);
        /* check connection */
        if ($base->connect_errno) {
            return 'Error '.$base->connect_errno.' connecting to database: '.$base->connect_error." Проверьте файл настроек подключения 'db.php'";
        }
        elseif ($number != 0)
        {
            //Проверка на наличие таблиц и их создание(если нет)
            $newTable = $base->query("
                CREATE TABLE 
                IF NOT EXISTS `new_table`(
                `login` VARCHAR(45) NOT NULL,
                `password` VARCHAR(100) NOT NULL,
                `email` VARCHAR(45) NOT NULL,
                `name` VARCHAR(45),
                `gender` VARCHAR (45),
                `date` DATE,
                `location` VARCHAR (45),
                `subscribers` MEDIUMTEXT,
                `subscriptions` MEDIUMTEXT,
                PRIMARY KEY (`email`, `login`))
                ENGINE = InnoDB
                DEFAULT CHARACTER SET = utf8");
            echo $base->error;
            $postUsers = $base->query("
                CREATE TABLE
                IF NOT EXISTS `postusers`(
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `login` VARCHAR (45) NOT NULL,
                `date` INT(11) NOT NULL,
                `post` VARCHAR(45) NOT NULL,
                PRIMARY KEY(`id`,`login`,`date`))
                ENGINE = InnoDB
                DEFAULT CHARACTER SET = utf8");
            echo $base->error;
            //наполнение таблиц
            if($newTable && $postUsers)
            {
                $nameGender = array(
                    'man' => array('Миша', 'Alex', 'Дмитрий', 'Алёшка','Василий','Генадий','Константин','Алексей'),
                    'voman' => array('Василиса', 'Екатерина','Оля','Даша','Ира','Виктория','Алёна')
                );
                $location = array('Пружаны','Москва','Брест','Берлин','Париж','Минск');

                //Формируем строку запросов
                $query = '';
                for($i = 0; $i < $number; $i++)
                {
                    $randGender = array_rand($nameGender,1);
                    $randName = $nameGender[$randGender][array_rand($nameGender[$randGender],1)];
                    $pass = password_hash(1,PASSWORD_DEFAULT);
                    $randDate = date('Y-m-d', rand(0,time()));
                    $randLocation = $location[array_rand($location)];
                    $query .= "INSERT INTO `new_table` (`login`,`password`,`name`,`email`,`gender`,`date`,`location`)
                              VALUES ('user".$i."','".$pass."','".$randName."','".$i."@mail.com','".$randGender."','".$randDate."','".$randLocation."');";
                }
                $result = $base->multi_query($query);
                $base->close();
                return $result;
            }
            else
            {
                $base->close;
                return false;
            }
        }
        else
        {
            $base->close;
            return true;
        }
    }
?>