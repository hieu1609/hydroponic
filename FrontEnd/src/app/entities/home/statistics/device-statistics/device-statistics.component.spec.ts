import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DeviceStatisticsComponent } from './device-statistics.component';

describe('DeviceStatisticsComponent', () => {
  let component: DeviceStatisticsComponent;
  let fixture: ComponentFixture<DeviceStatisticsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DeviceStatisticsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DeviceStatisticsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
