SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `prokureaki` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

CREATE TABLE IF NOT EXISTS `prokureaki`.`glo_pai` (
  `glo_id_pai` INT(11) NOT NULL AUTO_INCREMENT,
  `glo_nome_pai` VARCHAR(25) NULL DEFAULT NULL,
  PRIMARY KEY (`glo_id_pai`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`glo_est` (
  `glo_id_est` INT(11) NOT NULL AUTO_INCREMENT,
  `glo_nome_est` VARCHAR(25) NULL DEFAULT NULL,
  `glo_uf_est` CHAR(2) NULL DEFAULT NULL,
  `glo_glopai_est` INT(11) NULL DEFAULT NULL,
  `glo_visivel_est` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  PRIMARY KEY (`glo_id_est`),
  INDEX `glo_est_fk_glo_pai_idx` (`glo_glopai_est` ASC),
  CONSTRAINT `glo_est_fk_glo_pai`
    FOREIGN KEY (`glo_glopai_est`)
    REFERENCES `prokureaki`.`glo_pai` (`glo_id_pai`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`glo_cid` (
  `glo_id_cid` INT(11) NOT NULL AUTO_INCREMENT,
  `glo_nome_cid` VARCHAR(50) NULL DEFAULT NULL,
  `glo_gloest_cid` INT(11) NULL DEFAULT NULL,
  `glo_visivel_cid` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  PRIMARY KEY (`glo_id_cid`),
  INDEX `dlv_cid__fk_dlv_est_idx` (`glo_gloest_cid` ASC),
  CONSTRAINT `dlv_cid_fk_dlv_est`
    FOREIGN KEY (`glo_gloest_cid`)
    REFERENCES `prokureaki`.`glo_est` (`glo_id_est`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`glo_end` (
  `glo_id_end` INT(11) NOT NULL AUTO_INCREMENT,
  `glo_cep_end` CHAR(8) NULL DEFAULT NULL COMMENT 'mascara: 00000-000',
  `glo_logradouro_end` VARCHAR(100) NULL DEFAULT NULL,
  `glo_globai_end` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`glo_id_end`),
  UNIQUE INDEX `glo_end_uk_cep` (`glo_cep_end` ASC),
  INDEX `glo_end_fk_glo_bai_idx` (`glo_globai_end` ASC),
  CONSTRAINT `glo_end_fk_glo_bai`
    FOREIGN KEY (`glo_globai_end`)
    REFERENCES `prokureaki`.`glo_bai` (`glo_id_bai`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`glo_bai` (
  `glo_id_bai` INT(11) NOT NULL AUTO_INCREMENT,
  `glo_nome_bai` VARCHAR(80) NULL DEFAULT NULL,
  `glo_glocid_bai` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`glo_id_bai`),
  INDEX `glo_bai_fk_glo_cid_idx` (`glo_glocid_bai` ASC),
  CONSTRAINT `glo_bai_fk_glo_cid`
    FOREIGN KEY (`glo_glocid_bai`)
    REFERENCES `prokureaki`.`glo_cid` (`glo_id_cid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_seg` (
  `bus_id_seg` INT(11) NOT NULL AUTO_INCREMENT,
  `bus_descricao_seg` VARCHAR(25) NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_seg`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_emp` (
  `bus_id_emp` INT(11) NOT NULL AUTO_INCREMENT,
  `bus_nome_emp` VARCHAR(50) NULL DEFAULT NULL,
  `bus_detalhamento_emp` VARCHAR(350) NULL DEFAULT NULL,
  `bus_tipopessoa_emp` CHAR(1) NULL DEFAULT NULL COMMENT 'f: fisica; j: juridica',
  `bus_cpfcnpj_emp` VARCHAR(14) NULL DEFAULT NULL,
  `bus_gloend_emp` INT(11) NULL DEFAULT NULL,
  `bus_numero_emp` VARCHAR(5) NULL DEFAULT NULL,
  `bus_complemento_emp` VARCHAR(50) NULL DEFAULT NULL,
  `bus_aberto_emp` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_ativo_emp` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_bususumod_emp` INT(11) NULL DEFAULT NULL,
  `bus_datahoramod_emp` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_emp`),
  INDEX `bus_emp_fk_glo_end_idx` (`bus_gloend_emp` ASC),
  INDEX `bus_emp_fk_bus_usu_mod_idx` (`bus_bususumod_emp` ASC),
  CONSTRAINT `bus_emp_fk_glo_end`
    FOREIGN KEY (`bus_gloend_emp`)
    REFERENCES `prokureaki`.`glo_end` (`glo_id_end`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `bus_emp_fk_bus_usu_mod`
    FOREIGN KEY (`bus_bususumod_emp`)
    REFERENCES `prokureaki`.`bus_usu` (`dlv_id_usu`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_ext` (
  `bus_id_ext` INT(11) NOT NULL AUTO_INCREMENT,
  `bus_busemp_ext` INT(11) NULL DEFAULT NULL,
  `bus_bustip_ext` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_ext`),
  INDEX `bus_exs_fk_bus_emp_idx` (`bus_busemp_ext` ASC),
  INDEX `bus_ext_fk_bus_tip_idx` (`bus_bustip_ext` ASC),
  CONSTRAINT `bus_ext_fk_bus_tip`
    FOREIGN KEY (`bus_bustip_ext`)
    REFERENCES `prokureaki`.`bus_tip` (`bus_id_tip`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `bus_ext_fk_bus_emp`
    FOREIGN KEY (`bus_busemp_ext`)
    REFERENCES `prokureaki`.`bus_emp` (`bus_id_emp`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_ext` (
  `bus_id_ext` INT(11) NOT NULL AUTO_INCREMENT,
  `bus_busemp_ext` INT(11) NULL DEFAULT NULL,
  `bus_fone_ext` VARCHAR(20) NULL DEFAULT NULL,
  `bus_tipo_ext` CHAR(1) NULL DEFAULT NULL COMMENT 't: telefone; c: celular; f: fax',
  `bus_bususumod_ext` INT(11) NULL DEFAULT NULL,
  `bus_datahoramod_ext` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_ext`),
  INDEX `bus_ext_fk_bus_emp_idx` (`bus_busemp_ext` ASC),
  INDEX `bus_ext_fk_bus_usu_mod_idx` (`bus_bususumod_ext` ASC),
  CONSTRAINT `dlv_ext_fk_dlv_emp`
    FOREIGN KEY (`bus_busemp_ext`)
    REFERENCES `prokureaki`.`bus_emp` (`bus_id_emp`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `dlv_ext_fk_dlv_usu_mod`
    FOREIGN KEY (`bus_bususumod_ext`)
    REFERENCES `prokureaki`.`bus_usu` (`dlv_id_usu`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_usu` (
  `dlv_id_usu` INT(11) NOT NULL AUTO_INCREMENT,
  `dlv_busemp_usu` INT(11) NULL DEFAULT NULL,
  `bus_nome_usu` VARCHAR(30) NULL DEFAULT NULL,
  `bus_login_usu` VARCHAR(15) NULL DEFAULT NULL,
  `bus_senha_usu` CHAR(32) NULL DEFAULT NULL COMMENT 'md5',
  `bus_busper_usu` INT(11) NULL DEFAULT NULL,
  `bus_ativo_usu` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_dlvusumod_usu` INT(11) NULL DEFAULT NULL,
  `bus_datahoramod_usu` DATETIME NULL DEFAULT NULL,
  `bus_kingsoft_usu` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  PRIMARY KEY (`dlv_id_usu`),
  INDEX `bus_usu_fk_bus_emp_idx` (`dlv_busemp_usu` ASC),
  INDEX `bus_usu_fk_bus_per_idx` (`bus_busper_usu` ASC),
  INDEX `bus_usu_fk_bus_usu_mod_idx` (`bus_dlvusumod_usu` ASC),
  UNIQUE INDEX `bus_usu_uk_login` (`dlv_busemp_usu` ASC, `bus_login_usu` ASC),
  CONSTRAINT `bus_usu_fk_bus_emp`
    FOREIGN KEY (`dlv_busemp_usu`)
    REFERENCES `prokureaki`.`bus_emp` (`bus_id_emp`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `bus_usu_fk_bus_per`
    FOREIGN KEY (`bus_busper_usu`)
    REFERENCES `prokureaki`.`bus_per` (`bus_id_per`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `bus_usu_fk_bus_usu_mod`
    FOREIGN KEY (`bus_dlvusumod_usu`)
    REFERENCES `prokureaki`.`bus_usu` (`dlv_id_usu`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_cat` (
  `bus_id_cat` INT(11) NOT NULL AUTO_INCREMENT,
  `bus_busemp_cat` INT(11) NULL DEFAULT NULL,
  `bus_descricao_cat` VARCHAR(35) NULL DEFAULT NULL,
  `bus_ordem_cat` TINYINT(4) NULL DEFAULT NULL,
  `bus_ativo_cat` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_bususumod_cat` INT(11) NULL DEFAULT NULL,
  `bus_datahoramod_cat` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_cat`),
  INDEX `bus_cat_fk_bus_emp_idx` (`bus_busemp_cat` ASC),
  INDEX `bus_cat_fk_bus_usu_mod_idx` (`bus_bususumod_cat` ASC),
  CONSTRAINT `bus_cat_fk_bus_emp`
    FOREIGN KEY (`bus_busemp_cat`)
    REFERENCES `prokureaki`.`bus_emp` (`bus_id_emp`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `bus_cat_fk_bus_usu_mod`
    FOREIGN KEY (`bus_bususumod_cat`)
    REFERENCES `prokureaki`.`bus_usu` (`dlv_id_usu`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_pro` (
  `bus_id_pro` INT(11) NOT NULL AUTO_INCREMENT,
  `bus_busemp_pro` INT(11) NULL DEFAULT NULL,
  `bus_descricao_pro` VARCHAR(35) NULL DEFAULT NULL,
  `bus_detalhamento_pro` VARCHAR(350) NULL DEFAULT NULL,
  `bus_buscat_pro` INT(11) NULL DEFAULT NULL,
  `bus_preco_pro` DECIMAL(15,2) NULL DEFAULT NULL,
  `bus_promocao_pro` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_ordem_pro` TINYINT(4) NULL DEFAULT NULL,
  `bus_ativo_pro` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_dlvusumod_pro` INT(11) NULL DEFAULT NULL,
  `bus_datahoramod_pro` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_pro`),
  INDEX `dlv_pro_fk_dlv_emp_idx` (`bus_busemp_pro` ASC),
  INDEX `dlv_pxa_fk_dlv_usu_mod_idx` (`bus_dlvusumod_pro` ASC),
  INDEX `dlv_pro_fk_dlv_cat_idx` (`bus_buscat_pro` ASC),
  CONSTRAINT `dlv_pro_fk_dlv_emp`
    FOREIGN KEY (`bus_busemp_pro`)
    REFERENCES `prokureaki`.`bus_emp` (`bus_id_emp`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `dlv_pro_fk_dlv_usu_mod`
    FOREIGN KEY (`bus_dlvusumod_pro`)
    REFERENCES `prokureaki`.`bus_usu` (`dlv_id_usu`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `dlv_pro_fk_dlv_cat`
    FOREIGN KEY (`bus_buscat_pro`)
    REFERENCES `prokureaki`.`bus_cat` (`bus_id_cat`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_per` (
  `bus_id_per` INT(11) NOT NULL AUTO_INCREMENT,
  `bus_busemp_per` INT(11) NULL DEFAULT NULL,
  `bus_descricao_per` VARCHAR(20) NULL DEFAULT NULL,
  `bus_cadperfil_per` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_cadusuario_per` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_alttelefone_per` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_althorario_per` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_cadcategoria_per` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_cadproduto_per` TINYINT(4) NULL DEFAULT NULL COMMENT '0: nao; 1: sim',
  `bus_bususumod_per` INT(11) NULL DEFAULT NULL,
  `bus_datahoramod_per` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_per`),
  INDEX `bus_per_fk_bus_emp_idx` (`bus_busemp_per` ASC),
  INDEX `bus_per_fk_bus_usu_mod_idx` (`bus_bususumod_per` ASC),
  CONSTRAINT `bus_per_fk_bus_emp`
    FOREIGN KEY (`bus_busemp_per`)
    REFERENCES `prokureaki`.`bus_emp` (`bus_id_emp`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `bus_per_fk_bus_usu_mod`
    FOREIGN KEY (`bus_bususumod_per`)
    REFERENCES `prokureaki`.`bus_usu` (`dlv_id_usu`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_exh` (
  `bus_id_exh` INT(11) NOT NULL AUTO_INCREMENT,
  `bus_busemp_exh` INT(11) NULL DEFAULT NULL,
  `bus_dia_exh` INT(11) NULL DEFAULT NULL COMMENT '1: domingo; 2: segunda; 3: terca; 4: quarta; 5: quinta; 6: sexta; 7: sabado',
  `bus_horaini_exh` TIME NULL DEFAULT NULL,
  `bus_horafin_exh` TIME NULL DEFAULT NULL,
  `bus_bususumod_exh` INT(11) NULL DEFAULT NULL,
  `bus_datahoramod_exh` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_exh`),
  INDEX `bus_exh_fk_bus_emp_idx` (`bus_busemp_exh` ASC),
  INDEX `bus_exh_fk_bus_usu_mod_idx` (`bus_bususumod_exh` ASC),
  CONSTRAINT `bus_exh_fk_bus_emp`
    FOREIGN KEY (`bus_busemp_exh`)
    REFERENCES `prokureaki`.`bus_emp` (`bus_id_emp`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `bus_exh_fk_bus_usu_mod`
    FOREIGN KEY (`bus_bususumod_exh`)
    REFERENCES `prokureaki`.`bus_usu` (`dlv_id_usu`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_cli` (
  `bus_id_cli` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `bus_nome_cli` VARCHAR(50) NULL DEFAULT NULL,
  `bus_email_cli` VARCHAR(50) NULL DEFAULT NULL,
  `bus_senha_cli` CHAR(32) NULL DEFAULT NULL,
  `bus_idfacebook_cli` VARCHAR(100) NULL DEFAULT NULL,
  `bus_fone_cli` VARCHAR(20) NULL DEFAULT NULL,
  `bus_dtaniversario_cli` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_cli`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_ecl` (
  `bus_id_ecl` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `bus_dlvcli_ecl` BIGINT(20) NULL DEFAULT NULL,
  `bus_gloend_ecl` INT(11) NULL DEFAULT NULL,
  `bus_numero_ecl` VARCHAR(5) NULL DEFAULT NULL,
  `bus_complemento_ecl` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_ecl`),
  INDEX `bus_cxe_fk_bus_cli_idx` (`bus_dlvcli_ecl` ASC),
  INDEX `bus_cxe_fk_glo_end_idx` (`bus_gloend_ecl` ASC),
  CONSTRAINT `bus_ecl_fk_bus_cli`
    FOREIGN KEY (`bus_dlvcli_ecl`)
    REFERENCES `prokureaki`.`bus_cli` (`bus_id_cli`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `bus_ecl_fk_glo_end`
    FOREIGN KEY (`bus_gloend_ecl`)
    REFERENCES `prokureaki`.`glo_end` (`glo_id_end`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_cfg` (
  `bus_fixo_cfg` TINYINT(4) NOT NULL COMMENT 'valor fixo: 1',
  `bus_versao_cfg` INT(11) NULL DEFAULT NULL,
  `bus_msginicial_cfg` VARCHAR(500) NULL DEFAULT NULL,
  `bus_bloquearapp_cfg` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`bus_fixo_cfg`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_tip` (
  `bus_id_tip` INT(11) NOT NULL,
  `bus_descricao_tip` VARCHAR(20) NULL DEFAULT NULL,
  `bus_busseg_tip` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_tip`),
  INDEX `bus_tip_fk_bus_seg_idx` (`bus_busseg_tip` ASC),
  CONSTRAINT `bus_tip_fk_bus_seg`
    FOREIGN KEY (`bus_busseg_tip`)
    REFERENCES `prokureaki`.`bus_seg` (`bus_id_seg`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_pla` (
  `bus_id_pla` INT(11) NOT NULL,
  `bus_descricao_pla` VARCHAR(20) NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_pla`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_met` (
  `bus_id_met` INT(11) NOT NULL,
  `bus_descricao_met` VARCHAR(25) NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_met`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_cxe` (
  `bus_id_cxe` BIGINT(20) NOT NULL,
  `bus_buscli_cxe` BIGINT(20) NULL DEFAULT NULL,
  `bus_busemp_cxe` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_cxe`),
  INDEX `bus_cxe_fk_bus_emp_idx` (`bus_busemp_cxe` ASC),
  INDEX `bus_cxe_fk_bus_cli_idx` (`bus_buscli_cxe` ASC),
  CONSTRAINT `bus_cxe_fk_bus_cli`
    FOREIGN KEY (`bus_buscli_cxe`)
    REFERENCES `prokureaki`.`bus_cli` (`bus_id_cli`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `bus_cxe_fk_bus_emp`
    FOREIGN KEY (`bus_busemp_cxe`)
    REFERENCES `prokureaki`.`bus_emp` (`bus_id_emp`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_rec` (
  `bus_id_rec` INT(11) NOT NULL,
  `bus_buspla_rec` INT(11) NULL DEFAULT NULL,
  `bus_quantidade_rec` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_rec`),
  INDEX `bus_rec_fk_bus_pla_idx` (`bus_buspla_rec` ASC),
  CONSTRAINT `bus_rec_fk_bus_pla`
    FOREIGN KEY (`bus_buspla_rec`)
    REFERENCES `prokureaki`.`bus_pla` (`bus_id_pla`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `prokureaki`.`bus_mxs` (
  `bus_id_mxs` INT(11) NOT NULL,
  `bus_busmet_mxs` INT(11) NULL DEFAULT NULL,
  `bus_busseg_mxs` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`bus_id_mxs`),
  INDEX `bus_mxs_fk_bus_met_idx` (`bus_busmet_mxs` ASC),
  INDEX `bus_mxs_fk_bus_seg_idx` (`bus_busseg_mxs` ASC),
  CONSTRAINT `bus_mxs_fk_bus_met`
    FOREIGN KEY (`bus_busmet_mxs`)
    REFERENCES `prokureaki`.`bus_met` (`bus_id_met`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `bus_mxs_fk_bus_seg`
    FOREIGN KEY (`bus_busseg_mxs`)
    REFERENCES `prokureaki`.`bus_seg` (`bus_id_seg`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
