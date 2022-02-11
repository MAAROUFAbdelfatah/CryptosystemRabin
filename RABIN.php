 <?php
    #fonction qui verefier si un nombre est premier ou non.
    #INPUT: n un nombre. 
    #OUTPUT: TRUE si n premier, sinon FALSE.
    function estPremier($n)
    {
        if ($n == 2) 
            return TRUE;
        if (($n % 2) == 0) 
            return FALSE;
        $c = (int)(sqrt($n));
        for ($i = 3; $i <= $c; $i += 2) {
            if (($n % $i) == 0) 
                return FALSE;
        }
        return TRUE;
    }

    # fonction de algorithme d'Euclide etendu.
    #INPUT: a, b deux nombre. 
    #OUTPUT: [u, v, d] u, v: les coefficients de BachetBezout, d : le plus grand diviseur commun.
    function EEA($a, $b, $t1, $t2)
    {
        $rest = $a % $b;
        $q = (int)($a / $b);
        $t3 = $t1 - $t2 * $q;
        $a = $b;
        $b = $rest;
        $t1 = $t2;
        $t2 = $t3;
        if ($b == 0) {
            return $t1;
        } else return EEA($a, $b, $t1, $t2);
    }

    #fonction pour verifier si un nombre mode 4 est egale 3.
    #INPUT: n le nomber donner. 
    #OUTPUT: TRUE si n = 3 [4] sinon FALSE.
    function is3mod4($n)
    {
        if($n % 4 == 3)
            return TRUE;
        return FALSE;
    }

    #fonction de la theoreme de reste chinoise.
    #INPUT: a un liste des nombre, m aussi une liste des donner, tel que X = ai [mi]. 
    #OUTPUT: R liste des valure possible pour X.
    function CRT($a, $m)
    {
        $len_m = count($m);
        $R = [0, 0, 0, 0];
        $M = 1;
        for ($i=0; $i <  $len_m; $i++)
            $M *= $m[$i];

        for ($i=0; $i < $len_m; $i++){
            $R[0] += $a[$i] * ($M / $m[$i]) * (EEA($m[$i], ($M / $m[$i]), 0, 1) < 0 ?
             EEA($m[$i], ($M / $m[$i]), 0, 1) + $m[$i] : EEA($m[$i], ($M / $m[$i]), 0, 1) ) ;
            $R[0] %= $M;
            if ($R[0] < 0 )
                $R[0] += $M;
            $R[1] -= $a[$i] * ($M / $m[$i]) * (EEA($m[$i], ($M / $m[$i]), 0, 1) < 0 ?
             EEA($m[$i], ($M / $m[$i]), 0, 1) + $m[$i] : EEA($m[$i], ($M / $m[$i]), 0, 1)) ;
            $R[1] %= $M;
            if ($R[1] < 0 ) 
                $R[1] += $M;
            if ($i%2 === 0)
            {
                $R[2] -= $a[$i] * ($M / $m[$i]) * (EEA($m[$i], ($M / $m[$i]), 0, 1) < 0 ? 
                EEA($m[$i], ($M / $m[$i]), 0, 1) + $m[$i] : EEA($m[$i], ($M / $m[$i]), 0, 1)) ;
                $R[2] %= $M;
                if ($R[2] < 0 ) 
                    $R[2] += $M;
            
                $R[3] += $a[$i] * ($M / $m[$i]) * (EEA($m[$i], ($M / $m[$i]), 0, 1) < 0 ? 
                EEA($m[$i], ($M / $m[$i]), 0, 1) + $m[$i] : EEA($m[$i], ($M / $m[$i]), 0, 1)) ;
                $R[3] %= $M;
                if ($R[3] < 0 ) 
                    $R[3] += $M;
            }else
            {
                $R[2] += $a[$i] * ($M / $m[$i]) * (EEA($m[$i], ($M / $m[$i]), 0, 1) < 0 ? 
                EEA($m[$i], ($M / $m[$i]), 0, 1) + $m[$i] : EEA($m[$i], ($M / $m[$i]), 0, 1) + $m[$i]) ;
                $R[2] %= $M;
                if ($R[2] < 0 ) 
                    $R[2] += $M;
                $R[3] -= $a[$i] * ($M / $m[$i]) * (EEA($m[$i], ($M / $m[$i]), 0, 1) < 0 ? 
                EEA($m[$i], ($M / $m[$i]), 0, 1) + $m[$i] : EEA($m[$i], ($M / $m[$i]), 0, 1) + $m[$i]) ; 
                $R[3] %= $M;
                if ($R[3] < 0 ) 
                    $R[3] += $M;
            }             
        }
        return $R;
    }

    #fonction pour cree les cles.
    #INPUT: p, q ddeux nombres premiers, les cles privee.
    #OUTPUT: n c'est le cle privee.
    function key_generation($p, $q)
    {
        if((!estPremier($p) || !estPremier($q))||(!is3mod4($p) || !is3mod4($q)))
        {
            print("modifier q et p");
            return;
        }

        return $p * $q;
    }

    #fonction  pour coder un message donner
    #INPUT: m c'est le message a coder, n c'est le cle publique. 
    #OUTPUT: le message coder.
    function rabin_encryption($m , $n)
    {
        return gmp_mod(gmp_pow($m, 2), $n);
    }

    #fonction  pour decoder un message donner
    #INPUT: c c'est le message a decoder, p, q sont les cles privee. 
    #OUTPUT: les messages decoder possible.
    function rabin_decryption($c, $p, $q)
    {
        $a = (int) gmp_mod(gmp_pow($c, (1 + $p) / 4), $p);
        $b = (int) gmp_mod(gmp_pow($c, (1 + $q) / 4), $q);
        return CRT([$a, $b],[$p, $q]);
    }





/// testing of CRT
    print(json_encode((CRT([1, 9], [7, 11])))."\n");

//testing of key_generation
    print(key_generation(7, 11)."\n");

/// testing of rabin_encryption
    print(rabin_encryption(20 , key_generation(7, 11))."\n");

/// testing of rabin_decryption
    print(json_encode(rabin_decryption(15, 7, 11))."\n");