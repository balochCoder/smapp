<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name'); // e.g., Europe, Asia, North America
            $table->string('flag')->nullable(); // URL or path to flag image
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });

        $countries = [
            ['name' => 'Afghanistan', 'flag' => 'https://flagcdn.com/af.svg'],
            ['name' => 'Albania', 'flag' => 'https://flagcdn.com/al.svg'],
            ['name' => 'Algeria', 'flag' => 'https://flagcdn.com/dz.svg'],
            ['name' => 'Andorra', 'flag' => 'https://flagcdn.com/ad.svg'],
            ['name' => 'Angola', 'flag' => 'https://flagcdn.com/ao.svg'],
            ['name' => 'Antigua and Barbuda', 'flag' => 'https://flagcdn.com/ag.svg'],
            ['name' => 'Argentina', 'flag' => 'https://flagcdn.com/ar.svg'],
            ['name' => 'Armenia', 'flag' => 'https://flagcdn.com/am.svg'],
            ['name' => 'Australia', 'flag' => 'https://flagcdn.com/au.svg'],
            ['name' => 'Austria', 'flag' => 'https://flagcdn.com/at.svg'],
            ['name' => 'Azerbaijan', 'flag' => 'https://flagcdn.com/az.svg'],
            ['name' => 'Bahamas', 'flag' => 'https://flagcdn.com/bs.svg'],
            ['name' => 'Bahrain', 'flag' => 'https://flagcdn.com/bh.svg'],
            ['name' => 'Bangladesh', 'flag' => 'https://flagcdn.com/bd.svg'],
            ['name' => 'Barbados', 'flag' => 'https://flagcdn.com/bb.svg'],
            ['name' => 'Belarus', 'flag' => 'https://flagcdn.com/by.svg'],
            ['name' => 'Belgium', 'flag' => 'https://flagcdn.com/be.svg'],
            ['name' => 'Belize', 'flag' => 'https://flagcdn.com/bz.svg'],
            ['name' => 'Benin', 'flag' => 'https://flagcdn.com/bj.svg'],
            ['name' => 'Bhutan', 'flag' => 'https://flagcdn.com/bt.svg'],
            ['name' => 'Bolivia', 'flag' => 'https://flagcdn.com/bo.svg'],
            ['name' => 'Bosnia and Herzegovina', 'flag' => 'https://flagcdn.com/ba.svg'],
            ['name' => 'Botswana', 'flag' => 'https://flagcdn.com/bw.svg'],
            ['name' => 'Brazil', 'flag' => 'https://flagcdn.com/br.svg'],
            ['name' => 'Brunei', 'flag' => 'https://flagcdn.com/bn.svg'],
            ['name' => 'Bulgaria', 'flag' => 'https://flagcdn.com/bg.svg'],
            ['name' => 'Burkina Faso', 'flag' => 'https://flagcdn.com/bf.svg'],
            ['name' => 'Burundi', 'flag' => 'https://flagcdn.com/bi.svg'],
            ['name' => 'Cabo Verde', 'flag' => 'https://flagcdn.com/cv.svg'],
            ['name' => 'Cambodia', 'flag' => 'https://flagcdn.com/kh.svg'],
            ['name' => 'Cameroon', 'flag' => 'https://flagcdn.com/cm.svg'],
            ['name' => 'Canada', 'flag' => 'https://flagcdn.com/ca.svg'],
            ['name' => 'Central African Republic', 'flag' => 'https://flagcdn.com/cf.svg'],
            ['name' => 'Chad', 'flag' => 'https://flagcdn.com/td.svg'],
            ['name' => 'Chile', 'flag' => 'https://flagcdn.com/cl.svg'],
            ['name' => 'China', 'flag' => 'https://flagcdn.com/cn.svg'],
            ['name' => 'Colombia', 'flag' => 'https://flagcdn.com/co.svg'],
            ['name' => 'Comoros', 'flag' => 'https://flagcdn.com/km.svg'],
            ['name' => 'Congo', 'flag' => 'https://flagcdn.com/cg.svg'],
            ['name' => 'Costa Rica', 'flag' => 'https://flagcdn.com/cr.svg'],
            ['name' => 'Croatia', 'flag' => 'https://flagcdn.com/hr.svg'],
            ['name' => 'Cuba', 'flag' => 'https://flagcdn.com/cu.svg'],
            ['name' => 'Cyprus', 'flag' => 'https://flagcdn.com/cy.svg'],
            ['name' => 'Czech Republic', 'flag' => 'https://flagcdn.com/cz.svg'],
            ['name' => 'Denmark', 'flag' => 'https://flagcdn.com/dk.svg'],
            ['name' => 'Djibouti', 'flag' => 'https://flagcdn.com/dj.svg'],
            ['name' => 'Dominica', 'flag' => 'https://flagcdn.com/dm.svg'],
            ['name' => 'Dominican Republic', 'flag' => 'https://flagcdn.com/do.svg'],
            ['name' => 'Ecuador', 'flag' => 'https://flagcdn.com/ec.svg'],
            ['name' => 'Egypt', 'flag' => 'https://flagcdn.com/eg.svg'],
            ['name' => 'El Salvador', 'flag' => 'https://flagcdn.com/sv.svg'],
            ['name' => 'Equatorial Guinea', 'flag' => 'https://flagcdn.com/gq.svg'],
            ['name' => 'Eritrea', 'flag' => 'https://flagcdn.com/er.svg'],
            ['name' => 'Estonia', 'flag' => 'https://flagcdn.com/ee.svg'],
            ['name' => 'Eswatini', 'flag' => 'https://flagcdn.com/sz.svg'],
            ['name' => 'Ethiopia', 'flag' => 'https://flagcdn.com/et.svg'],
            ['name' => 'Fiji', 'flag' => 'https://flagcdn.com/fj.svg'],
            ['name' => 'Finland', 'flag' => 'https://flagcdn.com/fi.svg'],
            ['name' => 'France', 'flag' => 'https://flagcdn.com/fr.svg'],
            ['name' => 'Gabon', 'flag' => 'https://flagcdn.com/ga.svg'],
            ['name' => 'Gambia', 'flag' => 'https://flagcdn.com/gm.svg'],
            ['name' => 'Georgia', 'flag' => 'https://flagcdn.com/ge.svg'],
            ['name' => 'Germany', 'flag' => 'https://flagcdn.com/de.svg'],
            ['name' => 'Ghana', 'flag' => 'https://flagcdn.com/gh.svg'],
            ['name' => 'Greece', 'flag' => 'https://flagcdn.com/gr.svg'],
            ['name' => 'Grenada', 'flag' => 'https://flagcdn.com/gd.svg'],
            ['name' => 'Guatemala', 'flag' => 'https://flagcdn.com/gt.svg'],
            ['name' => 'Guinea', 'flag' => 'https://flagcdn.com/gn.svg'],
            ['name' => 'Guinea-Bissau', 'flag' => 'https://flagcdn.com/gw.svg'],
            ['name' => 'Guyana', 'flag' => 'https://flagcdn.com/gy.svg'],
            ['name' => 'Haiti', 'flag' => 'https://flagcdn.com/ht.svg'],
            ['name' => 'Honduras', 'flag' => 'https://flagcdn.com/hn.svg'],
            ['name' => 'Hungary', 'flag' => 'https://flagcdn.com/hu.svg'],
            ['name' => 'Iceland', 'flag' => 'https://flagcdn.com/is.svg'],
            ['name' => 'India', 'flag' => 'https://flagcdn.com/in.svg'],
            ['name' => 'Indonesia', 'flag' => 'https://flagcdn.com/id.svg'],
            ['name' => 'Iran', 'flag' => 'https://flagcdn.com/ir.svg'],
            ['name' => 'Iraq', 'flag' => 'https://flagcdn.com/iq.svg'],
            ['name' => 'Ireland', 'flag' => 'https://flagcdn.com/ie.svg'],
            // ['name' => 'Israel', 'flag' => 'https://flagcdn.com/il.svg'],
            ['name' => 'Italy', 'flag' => 'https://flagcdn.com/it.svg'],
            ['name' => 'Jamaica', 'flag' => 'https://flagcdn.com/jm.svg'],
            ['name' => 'Japan', 'flag' => 'https://flagcdn.com/jp.svg'],
            ['name' => 'Jordan', 'flag' => 'https://flagcdn.com/jo.svg'],
            ['name' => 'Kazakhstan', 'flag' => 'https://flagcdn.com/kz.svg'],
            ['name' => 'Kenya', 'flag' => 'https://flagcdn.com/ke.svg'],
            ['name' => 'Kiribati', 'flag' => 'https://flagcdn.com/ki.svg'],
            ['name' => 'Kuwait', 'flag' => 'https://flagcdn.com/kw.svg'],
            ['name' => 'Kyrgyzstan', 'flag' => 'https://flagcdn.com/kg.svg'],
            ['name' => 'Laos', 'flag' => 'https://flagcdn.com/la.svg'],
            ['name' => 'Latvia', 'flag' => 'https://flagcdn.com/lv.svg'],
            ['name' => 'Lebanon', 'flag' => 'https://flagcdn.com/lb.svg'],
            ['name' => 'Lesotho', 'flag' => 'https://flagcdn.com/ls.svg'],
            ['name' => 'Liberia', 'flag' => 'https://flagcdn.com/lr.svg'],
            ['name' => 'Libya', 'flag' => 'https://flagcdn.com/ly.svg'],
            ['name' => 'Liechtenstein', 'flag' => 'https://flagcdn.com/li.svg'],
            ['name' => 'Lithuania', 'flag' => 'https://flagcdn.com/lt.svg'],
            ['name' => 'Luxembourg', 'flag' => 'https://flagcdn.com/lu.svg'],
            ['name' => 'Madagascar', 'flag' => 'https://flagcdn.com/mg.svg'],
            ['name' => 'Malawi', 'flag' => 'https://flagcdn.com/mw.svg'],
            ['name' => 'Malaysia', 'flag' => 'https://flagcdn.com/my.svg'],
            ['name' => 'Maldives', 'flag' => 'https://flagcdn.com/mv.svg'],
            ['name' => 'Mali', 'flag' => 'https://flagcdn.com/ml.svg'],
            ['name' => 'Malta', 'flag' => 'https://flagcdn.com/mt.svg'],
            ['name' => 'Marshall Islands', 'flag' => 'https://flagcdn.com/mh.svg'],
            ['name' => 'Mauritania', 'flag' => 'https://flagcdn.com/mr.svg'],
            ['name' => 'Mauritius', 'flag' => 'https://flagcdn.com/mu.svg'],
            ['name' => 'Mexico', 'flag' => 'https://flagcdn.com/mx.svg'],
            ['name' => 'Micronesia', 'flag' => 'https://flagcdn.com/fm.svg'],
            ['name' => 'Moldova', 'flag' => 'https://flagcdn.com/md.svg'],
            ['name' => 'Monaco', 'flag' => 'https://flagcdn.com/mc.svg'],
            ['name' => 'Mongolia', 'flag' => 'https://flagcdn.com/mn.svg'],
            ['name' => 'Montenegro', 'flag' => 'https://flagcdn.com/me.svg'],
            ['name' => 'Morocco', 'flag' => 'https://flagcdn.com/ma.svg'],
            ['name' => 'Mozambique', 'flag' => 'https://flagcdn.com/mz.svg'],
            ['name' => 'Myanmar', 'flag' => 'https://flagcdn.com/mm.svg'],
            ['name' => 'Namibia', 'flag' => 'https://flagcdn.com/na.svg'],
            ['name' => 'Nauru', 'flag' => 'https://flagcdn.com/nr.svg'],
            ['name' => 'Nepal', 'flag' => 'https://flagcdn.com/np.svg'],
            ['name' => 'Netherlands', 'flag' => 'https://flagcdn.com/nl.svg'],
            ['name' => 'New Zealand', 'flag' => 'https://flagcdn.com/nz.svg'],
            ['name' => 'Nicaragua', 'flag' => 'https://flagcdn.com/ni.svg'],
            ['name' => 'Niger', 'flag' => 'https://flagcdn.com/ne.svg'],
            ['name' => 'Nigeria', 'flag' => 'https://flagcdn.com/ng.svg'],
            ['name' => 'North Korea', 'flag' => 'https://flagcdn.com/kp.svg'],
            ['name' => 'North Macedonia', 'flag' => 'https://flagcdn.com/mk.svg'],
            ['name' => 'Norway', 'flag' => 'https://flagcdn.com/no.svg'],
            ['name' => 'Oman', 'flag' => 'https://flagcdn.com/om.svg'],
            ['name' => 'Pakistan', 'flag' => 'https://flagcdn.com/pk.svg'],
            ['name' => 'Palau', 'flag' => 'https://flagcdn.com/pw.svg'],
            ['name' => 'Palestine', 'flag' => 'https://flagcdn.com/ps.svg'],
            ['name' => 'Panama', 'flag' => 'https://flagcdn.com/pa.svg'],
            ['name' => 'Papua New Guinea', 'flag' => 'https://flagcdn.com/pg.svg'],
            ['name' => 'Paraguay', 'flag' => 'https://flagcdn.com/py.svg'],
            ['name' => 'Peru', 'flag' => 'https://flagcdn.com/pe.svg'],
            ['name' => 'Philippines', 'flag' => 'https://flagcdn.com/ph.svg'],
            ['name' => 'Poland', 'flag' => 'https://flagcdn.com/pl.svg'],
            ['name' => 'Portugal', 'flag' => 'https://flagcdn.com/pt.svg'],
            ['name' => 'Qatar', 'flag' => 'https://flagcdn.com/qa.svg'],
            ['name' => 'Romania', 'flag' => 'https://flagcdn.com/ro.svg'],
            ['name' => 'Russia', 'flag' => 'https://flagcdn.com/ru.svg'],
            ['name' => 'Rwanda', 'flag' => 'https://flagcdn.com/rw.svg'],
            ['name' => 'Saint Kitts and Nevis', 'flag' => 'https://flagcdn.com/kn.svg'],
            ['name' => 'Saint Lucia', 'flag' => 'https://flagcdn.com/lc.svg'],
            ['name' => 'Saint Vincent and the Grenadines', 'flag' => 'https://flagcdn.com/vc.svg'],
            ['name' => 'Samoa', 'flag' => 'https://flagcdn.com/ws.svg'],
            ['name' => 'San Marino', 'flag' => 'https://flagcdn.com/sm.svg'],
            ['name' => 'Sao Tome and Principe', 'flag' => 'https://flagcdn.com/st.svg'],
            ['name' => 'Saudi Arabia', 'flag' => 'https://flagcdn.com/sa.svg'],
            ['name' => 'Senegal', 'flag' => 'https://flagcdn.com/sn.svg'],
            ['name' => 'Serbia', 'flag' => 'https://flagcdn.com/rs.svg'],
            ['name' => 'Seychelles', 'flag' => 'https://flagcdn.com/sc.svg'],
            ['name' => 'Sierra Leone', 'flag' => 'https://flagcdn.com/sl.svg'],
            ['name' => 'Singapore', 'flag' => 'https://flagcdn.com/sg.svg'],
            ['name' => 'Slovakia', 'flag' => 'https://flagcdn.com/sk.svg'],
            ['name' => 'Slovenia', 'flag' => 'https://flagcdn.com/si.svg'],
            ['name' => 'Solomon Islands', 'flag' => 'https://flagcdn.com/sb.svg'],
            ['name' => 'Somalia', 'flag' => 'https://flagcdn.com/so.svg'],
            ['name' => 'South Africa', 'flag' => 'https://flagcdn.com/za.svg'],
            ['name' => 'South Korea', 'flag' => 'https://flagcdn.com/kr.svg'],
            ['name' => 'South Sudan', 'flag' => 'https://flagcdn.com/ss.svg'],
            ['name' => 'Spain', 'flag' => 'https://flagcdn.com/es.svg'],
            ['name' => 'Sri Lanka', 'flag' => 'https://flagcdn.com/lk.svg'],
            ['name' => 'Sudan', 'flag' => 'https://flagcdn.com/sd.svg'],
            ['name' => 'Suriname', 'flag' => 'https://flagcdn.com/sr.svg'],
            ['name' => 'Sweden', 'flag' => 'https://flagcdn.com/se.svg'],
            ['name' => 'Switzerland', 'flag' => 'https://flagcdn.com/ch.svg'],
            ['name' => 'Syria', 'flag' => 'https://flagcdn.com/sy.svg'],
            ['name' => 'Taiwan', 'flag' => 'https://flagcdn.com/tw.svg'],
            ['name' => 'Tajikistan', 'flag' => 'https://flagcdn.com/tj.svg'],
            ['name' => 'Tanzania', 'flag' => 'https://flagcdn.com/tz.svg'],
            ['name' => 'Thailand', 'flag' => 'https://flagcdn.com/th.svg'],
            ['name' => 'Timor-Leste', 'flag' => 'https://flagcdn.com/tl.svg'],
            ['name' => 'Togo', 'flag' => 'https://flagcdn.com/tg.svg'],
            ['name' => 'Tonga', 'flag' => 'https://flagcdn.com/to.svg'],
            ['name' => 'Trinidad and Tobago', 'flag' => 'https://flagcdn.com/tt.svg'],
            ['name' => 'Tunisia', 'flag' => 'https://flagcdn.com/tn.svg'],
            ['name' => 'Turkey', 'flag' => 'https://flagcdn.com/tr.svg'],
            ['name' => 'Turkmenistan', 'flag' => 'https://flagcdn.com/tm.svg'],
            ['name' => 'Tuvalu', 'flag' => 'https://flagcdn.com/tv.svg'],
            ['name' => 'Uganda', 'flag' => 'https://flagcdn.com/ug.svg'],
            ['name' => 'Ukraine', 'flag' => 'https://flagcdn.com/ua.svg'],
            ['name' => 'United Arab Emirates', 'flag' => 'https://flagcdn.com/ae.svg'],
            ['name' => 'United Kingdom', 'flag' => 'https://flagcdn.com/gb.svg'],
            ['name' => 'United States', 'flag' => 'https://flagcdn.com/us.svg'],
            ['name' => 'Uruguay', 'flag' => 'https://flagcdn.com/uy.svg'],
            ['name' => 'Uzbekistan', 'flag' => 'https://flagcdn.com/uz.svg'],
            ['name' => 'Vanuatu', 'flag' => 'https://flagcdn.com/vu.svg'],
            ['name' => 'Vatican City', 'flag' => 'https://flagcdn.com/va.svg'],
            ['name' => 'Venezuela', 'flag' => 'https://flagcdn.com/ve.svg'],
            ['name' => 'Vietnam', 'flag' => 'https://flagcdn.com/vn.svg'],
            ['name' => 'Yemen', 'flag' => 'https://flagcdn.com/ye.svg'],
            ['name' => 'Zambia', 'flag' => 'https://flagcdn.com/zm.svg'],
            ['name' => 'Zimbabwe', 'flag' => 'https://flagcdn.com/zw.svg'],
        ];

        // Insert countries in chunks for better performance
        collect($countries)->chunk(50)->each(function ($chunk) {
            $now = now();
            $records = $chunk->map(function ($country) use ($now) {
                return [
                    'id' => (string) Str::ulid(),
                    'name' => $country['name'],
                    'flag' => $country['flag'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->toArray();

            DB::table('countries')->insert($records);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
