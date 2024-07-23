<table class="table table-light table-hover text-black-50">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">No Rawat</th>
                            <th scope="col">Pasien</th>
                            <th scope="col">Menggunakan</th>
                            <th scope="col">Pemeriksaan Dokter</th>
                            <th scope="col">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $totalBelumBayar = 0; // Menyimpan total belum bayar
                            while ($row = mysqli_fetch_assoc($queryDataTabel)) 
                            {
                                $rawat = $row['rawat'];
                                $pasien = $row['pasien'];
                                $menggunakan = $row['menggunakan'];
                                $Pemeriksaan_Dokter = $row['Pemeriksaan_Dokter'];
                                $keterangan = $row['Keterangan'];
                                // Menambahkan nilai belum bayar ke totalBelumBayar
                                //$totalBelumBayar += $belumbayar;
                                ?>
                                    <tr>
                                    <th scope="row"><?php echo $no; ?></th>
                                    <td><?php echo $rawat; ?></td>
                                    <td><?php echo $pasien; ?></td>
                                    <td><?php echo $menggunakan; ?></td>
                                    <td><?php echo $Pemeriksaan_Dokter; ?></td>
                                    <td><?php echo $keterangan; ?></td>
                                    </tr>
                                <?php
                                $no++;
                            }
                        ?>
                    </tbody>
                </table>